<?php

namespace App\Command;

use App\Autoload\DoctrineAware;
use App\Entity\Planet;
use App\Entity\Planet\InfrastructureReport;
use App\FIO\Client;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'fio:import:infrastructure-report',
)]
class FioImportInfrastructureReportCommand extends Command
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
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $planetNaturalId = $input->getArgument('natural-id');

        $io->info('Importing Population Infrastructure Report for ' . $planetNaturalId);

        $planet = $this->findEntityBy(Planet::class, ['naturalId' => $planetNaturalId]);
        if (!$planet) {
            $io->error("Planet not found");
            return Command::FAILURE;
        }

        $io->write('Reading data from FIO...');
        $fioInfraReport = $this->fio->getInfrastructureReport($planetNaturalId);
        $io->writeln(' done');
        $io->writeln('');

        // Population
        $populationTypes = ['pioneer', 'settler', 'technician', 'engineer', 'scientist'];
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($fioInfraReport->InfrastructureReports as $popr) {
            $report = $this->getReport($planet, $popr->SimulationPeriod);

            $report->setIsExplorersGraceEnabled($popr->ExplorersGraceEnabled);
            $report->setStartedAt(new DateTimeImmutable('@' . ($popr->TimestampMs / 1000)));

            foreach ($populationTypes as $type) {
                /** @var InfrastructureReport\Population $population */
                $population = $accessor->getValue($report, "{$type}s");

                $type = ucfirst($type);
                $population
                    ->setAmount($accessor->getValue($popr, "NextPopulation$type"))
                    ->setDifference($accessor->getValue($popr, "PopulationDifference$type"))
                    ->setAverageHappiness($accessor->getValue($popr, "AverageHappiness$type"))
                    ->setUnemploymentRate($accessor->getValue($popr, "UnemploymentRate$type"))
                    ->setOpenJobs($accessor->getValue($popr, "OpenJobs$type"));

                $report->addPopulation($population);
            }

            $this->persistEntity($report);
            $this->flushEntities();
        }

        $this->flushEntities();

        return Command::SUCCESS;
    }

    /**
     * Returns and existing report or creates a new one, if missing.
     */
    private function getReport(Planet $planet, ?int $simulationPeriod): InfrastructureReport
    {
        // find an existing report first
        $report = $this->findEntityBy(
            InfrastructureReport::class,
            [
                'planet' => $planet,
                'simulationPeriod' => $simulationPeriod,
            ]
        );

        // if none exists, create a new one
        if (null === $report) {
            $report = (new InfrastructureReport())
                ->setPlanet($planet)
                ->setSimulationPeriod($simulationPeriod);
        }

        return $report;
    }
}
