<?php

namespace App\Command;

use App\Autoload\DoctrineAware;
use App\Entity\Building;
use App\Entity\Expertise;
use App\Entity\Material;
use App\FIO\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fio:import:buildings',
    description: 'Add a short description for your command',
)]
class FioImportBuildingsCommand extends Command
{
    use DoctrineAware;


    public function __construct(
        private readonly Client $fio,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Importing Buildings');

        $io->write('Collecting data from FIO...');
        $fioBuildings = $this->fio->getBuildings();
        $io->writeln(' done');
        $io->writeln('');

        $total = $fioBuildings->count();
        $io->progressStart($total);
        foreach ($fioBuildings as $_building) {
            $building = $this->findEntityBy(Building::class, ['fioId' => $_building->BuildingId]) ?: new Building();

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
                $expertise = $this->findEntityBy(Expertise::class, ['name' => $_building->Expertise])
                    ?: (new Expertise())->setName($_building->Expertise);

                $building->setExpertise($expertise);

                $this->persistEntity($expertise);
            }

            // Construction Costs
            // only save construction costs if missing
            if ($building->getConstructionCosts()->isEmpty()) {
                foreach ($_building->BuildingCosts as $_constructionCost) {
                    $material = $this->getMaterialByTicker($_constructionCost['CommodityTicker']);

                    $cost = (new Building\Cost())
                        ->setAmount($_constructionCost['Amount'])
                        ->setMaterial($material)
                        ->setBuilding($building);

                    $this->persistEntity($cost);
                }
            }

            // Recipes
            foreach ($_building->Recipes as $_recipe) {
                $lookup = $this->findEntityBy(
                    Building\Recipe::class,
                    ['standardName' => $_recipe['StandardRecipeName']]
                );

                // only save recipes you don't have yet
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

                    $this->persistEntity($recipe);
                }
            }

            $this->persistEntity($building);
            $this->flushEntities();

            $io->progressAdvance();
        }
        $io->progressFinish();

        $io->success("$total buildings imported");

        return Command::SUCCESS;
    }

    private function getMaterialByTicker(string $ticker): ?Material
    {
        return $this->findEntityBy(Material::class, ['ticker' => $ticker]);
    }
}
