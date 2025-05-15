<?php

namespace App\Command;

use App\Entity\Building;
use App\Entity\Company;
use App\Entity\Expertise;
use App\Entity\Material;
use App\Entity\Material\Category;
use App\Entity\Planet;
use App\Entity\Workforce;
use App\FIO\Client;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:fio:sync',
    description: 'Synchronizes the local database with the FIO API.',
)]
class FioSyncCommand extends Command
{
    private OutputInterface $output;
    private SymfonyStyle $io;


    public function __construct(
        private readonly Client $fio,
        private readonly ManagerRegistry $doctrine,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);

        $this->importMaterials();
        $this->importBuildings();
        $this->importWorkforceNeeds();
        // todo Buildings
        // todo Systems
        $this->importPlanets();
        // todo Infrastructure Reports

        return Command::SUCCESS;
    }

    private function importMaterials(): void
    {
        $materialRepo = $this->doctrine->getRepository(Material::class);
        $categoryRepo = $this->doctrine->getRepository(Category::class);

        // Get all materials.
        $this->logDebug('[FIO] requesting "material/allmaterial" ...');

        $materials = $this->fio->getMaterials();
        $total = $materials->count();

        $this->logDebug("[FIO] received $total materials.");

        foreach ($materials as $i => $material) {
            $entity = $materialRepo->findOneBy(['fioId' => $material->MaterialId]) ?? new Material();
            $category = $categoryRepo->findOneBy(['fioId' => $material->CategoryId]) ?? new Category();

            $entity->setFioId($material->MaterialId);
            $entity->setName($material->Name);
            $entity->setTicker($material->Ticker);
            $entity->setMass($material->Weight);
            $entity->setVolume($material->Volume);

            $category->setFioId($material->CategoryId);
            $category->setName($material->CategoryName);
            $entity->setCategory($category);

            $materialRepo->persist($entity);
            $categoryRepo->persist($category, true);

            $ticker = $entity->getTicker();
            // $this->logDebug("$ticker imported");
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function importPlanets(): void
    {
        $planetRepo = $this->doctrine->getRepository(Planet::class);
        $now = new DateTimeImmutable();

        /// Get all planets.

        $this->logDebug('[FIO] requesting "planet/allplanet" ...');

        $planets = $this->fio->getPlanets();
        $total = $planets->count();

        $this->logDebug("[FIO] received $total planets.");

        /// Collect details to every planet.

        foreach ($planets as $i => $planet) {
            $i += 1;
            $naturalId = $planet->PlanetNaturalId;

            $this->io->writeln("($i/$total) $naturalId");

            $this->logDebug("[FIO] requesting planet details for $naturalId ...");
            $planet = $this->fio->getPlanet($naturalId, $planet);

            // load the existing planet entity, if exists
            $entity = $planetRepo->findOneBy(['naturalId' => $naturalId]) ?? new Planet();;

            // update data
            $entity->setFioId($planet->PlanetId);
            $entity->setNaturalId($planet->PlanetNaturalId);
            $entity->setName($planet->PlanetName);
            $entity->setGravity($planet->Gravity);
            $entity->setTemperature($planet->Temperature);
            $entity->setPressure($planet->Pressure);
            $entity->setHasSurface($planet->Surface);
            $entity->setFertility($planet->Fertility);

            // resources
            $this->io->writeln("  - collecting resources");
            foreach ($planet->Resources as $resource) {
                // check, if the resource already exists
                $resourceEntity = $entity->getResources()->filter(
                    function (Planet\Resource $_resource) use ($resource) {
                        return $_resource->getMaterial()->getFioId() == $resource['MaterialId'];
                    }
                )->first() ?: new Planet\Resource();

                $material = $this->doctrine->getRepository(Material::class)->findOneBy(
                    ['fioId' => $resource['MaterialId']]
                );
                $resourceEntity->setMaterial($material);
                $resourceEntity->setFactor($resource['Factor']);
                $resourceEntity->setType($resource['ResourceType']);;

                $entity->addResource($resourceEntity);
            }

            // Import planetary infrastructure
            $this->io->writeln("  - importing planetary infrastructure");
            $infrastructure = [];

            $infrastructure[] = $planet->HasAdministrationCenter ? Planet::INFRASTRUCTURE_ADMINISTRATION_CENTER : null;
            $infrastructure[] = $planet->HasWarehouse ? Planet::INFRASTRUCTURE_WAREHOUSE : null;
            $infrastructure[] = $planet->HasShipyard ? Planet::INFRASTRUCTURE_SHIPYARD : null;
            $infrastructure[] = $planet->HasLocalMarket ? Planet::INFRASTRUCTURE_LOCAL_MARKET : null;
            $infrastructure[] = $planet->HasChamberOfCommerce ? Planet::INFRASTRUCTURE_CHAMBER_OF_GLOBAL_COMMERCE : null;

            $entity->setPlanetaryInfrastructure(array_filter($infrastructure));

            // Import Population Infrastructure
            $this->io->writeln("  - importing population infrastructure");
            $popi = $this->fio->getInfrastructureReport($naturalId);


            // Import planet sites
            $this->io->writeln("  - importing planet sites");
            $this->importPlanetSites($entity);

            // Import CoGC
            if ($entity->hasChamberOfGlobalCommerce() && null !== $planet->COGCProgramStatus) {
                $this->io->writeln("  - importing CoGC program");

                $program = array_filter($planet->COGCPrograms, function (array $program) use ($now) {
                    return $program['StartEpochMs'] <= $now->getTimestamp() * 1000
                        && $program['EndEpochMs'] >= $now->getTimestamp() * 1000;
                });

                if (count($program) == 1) {
                    $program = array_values($program)[0];

                    $cogcProgram = (new Planet\CoGCProgram())
                        ->setType($program['ProgramType'])
                        ->setStartedAt(new DateTimeImmutable('@' . intval($program['StartEpochMs'] / 1000)))
                        ->setEndedAt(new DateTimeImmutable('@' . intval($program['EndEpochMs'] / 1000)))
                        ->setStatus($planet->COGCProgramStatus);

                    $entity->setCoGCProgram($cogcProgram);

                    $this->doctrine->getManagerForClass(Planet\CoGCProgram::class)->persist($cogcProgram);
                }
            }

            // todo: Population Infrastructure
            // todo: Local Rules
            // todo: System

            // persist and push
            $planetRepo->persist($entity, true);
            $this->doctrine->getManagerForClass(Planet::class)->clear();
        }
    }

    private function importPlanetSites(Planet $planet): void
    {
        $naturalId = $planet->getNaturalId();

        $_em = $this->doctrine->getManagerForClass(Planet\Site::class);

        $this->logDebug("[FIO] requesting sites for $naturalId ...");
        $sites = $this->fio->getPlanetSites($naturalId);

        foreach ($sites as $_site) {
            $site = $this->doctrine
                ->getRepository(Planet\Site::class)
                ->findOneBy(['siteId' => $_site->SiteId])
                ?: new Planet\Site();

            $site
                ->setSiteId($_site->SiteId)
                ->setPlotId($_site->PlotId)
                ->setPlotNumber($_site->PlotNumber)
                ->setPlanet($planet);

            if (null !== $_site->OwnerCode) {
                $company = $this->doctrine
                    ->getRepository(Company::class)
                    ->findOneBy(['fioId' => $_site->OwnerId])
                    ?: new Company();

                $company
                    ->setFioId($_site->OwnerId)
                    ->setName($_site->OwnerName)
                    ->setCode($_site->OwnerCode);

                $site->setOwner($company);

                $_em->persist($company);;
            }

            $_em->persist($site);
        }
    }


    private function logDebug(string $msg): void
    {
        $this->io->writeln("<fg=gray>$msg</>");
    }

    private function importWorkforceNeeds(): void
    {
        $this->io->writeln("Importing workforce needs");
        $needs = $this->fio->getWorkforceNeeds();

        foreach ($needs as $workforceNeed) {
            $workforce = $this->doctrine
                ->getRepository(Workforce::class)
                ->findOneBy(['type' => $workforceNeed->WorkforceType])
                ?: new Workforce();

            $workforce->setType($workforceNeed->WorkforceType);

            // Reset Workforce Needs
            foreach ($workforce->getNeeds() as $need) {
                $workforce->removeNeed($need);
                $this->doctrine->getManagerForClass(Workforce\Need::class)->remove($need);
            }

            foreach ($workforceNeed->Needs as $need) {
                $material = $this->doctrine
                    ->getRepository(Material::class)
                    ->findOneBy(['fioId' => $need['MaterialId']]);

                $_need = (new Workforce\Need())
                    ->setMaterial($material)
                    ->setWorkforce($workforce)
                    ->setAmount($need['Amount']);

                $this->doctrine->getManagerForClass(Workforce\Need::class)->persist($_need);
            }

            $this->doctrine->getManagerForClass(Workforce::class)->persist($workforce);
        }

        $this->doctrine->getManagerForClass(Workforce::class)->flush();
        $this->doctrine->getManagerForClass(Workforce::class)->clear();
    }

    private function importBuildings(): void
    {
        $this->io->writeln("Importing buildings");
        $fioBuildings = $this->fio->getBuildings();
        $em = $this->doctrine->getManager();

        foreach ($fioBuildings as $_building) {
            $building = $this->findOneByFioId(Building::class, $_building->BuildingId) ?: new Building();

            $building
                ->setFioId($_building->BuildingId)
                ->setName($_building->Name)
                ->setTicker($_building->Ticker)
                ->setAreaCost($_building->AreaCost)
                ->setRequiredPioneers($_building->Pioneers)
                ->setRequiredSettlers($_building->Settlers)
                ->setRequiredTechnicians($_building->Technicians)
                ->setRequiredEngineers($_building->Engineers)
                ->setRequiredScientists($_building->Scientists);

            if ($_building->Expertise) {
                $expertise = $this->getRepo(Expertise::class)->findOneBy(['name' => $_building->Expertise])
                    ?: (new Expertise())->setName($_building->Expertise);

                $building->setExpertise($expertise);
                $em->persist($expertise);
            }

            // Construction Costs
            if ($building->getConstructionCosts()->isEmpty()) {
                foreach ($_building->BuildingCosts as $_constructionCost) {
                    $material = $this
                        ->getRepo(Material::class)
                        ->findOneBy(['ticker' => $_constructionCost['CommodityTicker']]);

                    $cost = (new Building\Cost())
                        ->setAmount($_constructionCost['Amount'])
                        ->setMaterial($material)
                        ->setBuilding($building);

                    $em->persist($cost);
                }
            }

            // Recipes
            foreach ($_building->Recipes as $_recipe) {
                $lookup = $this
                    ->getRepo(Building\Recipe::class)
                    ->findOneBy(['standardName' => $_recipe['StandardRecipeName']]);

                if (!$lookup) {
                    $recipe = (new Building\Recipe())
                        ->setBuilding($building)
                        ->setName($_recipe['RecipeName'])
                        ->setStandardName($_recipe['StandardRecipeName'])
                        ->setDurationMs($_recipe['DurationMs']);

                    foreach ($_recipe['Inputs'] as $_input) {
                        $recipe->addInput(
                            (new Building\Recipe\Ingredient())
                                ->setMaterial($this->getMaterialByTicker($_input['CommodityTicker']))
                                ->setAmount($_input['Amount'])
                        );
                    }

                    foreach ($_recipe['Outputs'] as $_output) {
                        $recipe->addOutput(
                            (new Building\Recipe\Ingredient())
                                ->setMaterial($this->getMaterialByTicker($_output['CommodityTicker']))
                                ->setAmount($_output['Amount'])
                        );
                    }

                    $em->persist($recipe);
                }
            }

            $em->persist($building);
            $em->flush();
        }
        $em->clear();
    }


    private function getRepo(string $entityFqcn): ObjectRepository
    {
        return $this->doctrine->getRepository($entityFqcn);
    }

    private function getMaterialByTicker(string $ticker): Material
    {
        return $this->getRepo(Material::class)->findOneBy(['ticker' => $ticker]);
    }

    private function findOneByFioId(string $fqcn, string $fioId)
    {
        return $this->getRepo($fqcn)->findOneBy(['fioId' => $fioId]);
    }
}
