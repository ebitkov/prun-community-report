<?php

namespace App\Command;

use App\Autoload\DoctrineAware;
use App\Entity\Material;
use App\Entity\Planet;
use App\Entity\System;
use App\FIO\Client;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'fio:import:planet',
)]
class FioImportPlanetCommand extends Command
{
    use DoctrineAware;


    public function __construct(
        private readonly Client $fio,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('natural-id', InputArgument::REQUIRED, "The planet's natural id (e.g. ZV-301a)");
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $planetNaturalId = $input->getArgument('natural-id');
        $now = new DateTimeImmutable();

        $io->info("Importing Planet $planetNaturalId from FIO");

        try {
            $io->write('Reading planet data from FIO...');
            $fioPlanet = $this->fio->getPlanet($planetNaturalId);
            $io->writeln(' done');
            $io->writeln('');
        } catch (Exception) {
            $io->error("No data for planet $planetNaturalId available!");
            return Command::FAILURE;
        }

        // load the existing planet entity, if exists
        $entity = $this->findEntityBy(Planet::class, ['naturalId' => $planetNaturalId]) ?: new Planet();

        // update data
        $entity->setFioId($fioPlanet->PlanetId);
        $entity->setNaturalId($fioPlanet->PlanetNaturalId);
        $entity->setName($fioPlanet->PlanetName);
        $entity->setGravity($fioPlanet->Gravity);
        $entity->setTemperature($fioPlanet->Temperature);
        $entity->setPressure($fioPlanet->Pressure);
        $entity->setHasSurface($fioPlanet->Surface);
        $entity->setFertility($fioPlanet->Fertility);
        $entity->setSystem($this->findEntityBy(System::class, ['fioId' => $fioPlanet->SystemId]));

        // resources
        $io->writeln(" - collecting resources");
        foreach ($fioPlanet->Resources as $resource) {
            // check, if the resource already exists
            $resourceEntity = $entity->getResources()->filter(
                function (Planet\Resource $_resource) use ($resource) {
                    return $_resource->getMaterial()->getFioId() == $resource['MaterialId'];
                }
            )->first() ?: new Planet\Resource();

            $material = $this->findEntityBy(
                Material::class,
                ['fioId' => $resource['MaterialId']]
            );
            $resourceEntity->setMaterial($material);
            $resourceEntity->setFactor($resource['Factor']);
            $resourceEntity->setType($resource['ResourceType']);;

            $entity->addResource($resourceEntity);
        }

        // Import planetary infrastructure
        $io->writeln(" - importing planetary infrastructure");
        $infrastructure = [];

        $infrastructure[] = $fioPlanet->HasAdministrationCenter ? Planet::INFRASTRUCTURE_ADMINISTRATION_CENTER : null;
        $infrastructure[] = $fioPlanet->HasWarehouse ? Planet::INFRASTRUCTURE_WAREHOUSE : null;
        $infrastructure[] = $fioPlanet->HasShipyard ? Planet::INFRASTRUCTURE_SHIPYARD : null;
        $infrastructure[] = $fioPlanet->HasLocalMarket ? Planet::INFRASTRUCTURE_LOCAL_MARKET : null;
        $infrastructure[] = $fioPlanet->HasChamberOfCommerce ? Planet::INFRASTRUCTURE_CHAMBER_OF_GLOBAL_COMMERCE : null;

        $entity->setPlanetaryInfrastructure(array_filter($infrastructure));

        // Import CoGC
        # todo: Import all CoGC Programs
        if ($entity->hasChamberOfGlobalCommerce() && null !== $fioPlanet->COGCProgramStatus) {
            $io->writeln(" - importing CoGC program");

            $program = array_filter($fioPlanet->COGCPrograms, function (array $program) use ($now) {
                return $program['StartEpochMs'] <= $now->getTimestamp() * 1000
                    && $program['EndEpochMs'] >= $now->getTimestamp() * 1000;
            });

            if (count($program) == 1) {
                $program = array_values($program)[0];

                $cogcProgram = (new Planet\CoGCProgram())
                    ->setType($program['ProgramType'])
                    ->setStartedAt(new DateTimeImmutable('@' . intval($program['StartEpochMs'] / 1000)))
                    ->setEndedAt(new DateTimeImmutable('@' . intval($program['EndEpochMs'] / 1000)))
                    ->setStatus($fioPlanet->COGCProgramStatus);

                $entity->setCoGCProgram($cogcProgram);

                $this->persistEntity($cogcProgram);
            }
        }

        // persist and push
        $this->persistEntity($entity);
        $this->flushEntities();

        return Command::SUCCESS;
    }
}
