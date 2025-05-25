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

        foreach ($fioInfraReport->InfrastructureReports as $fioReportItem) {
            $report = $this->findEntityBy(
                InfrastructureReport::class,
                ['simulationPeriod' => $fioReportItem->SimulationPeriod]
            ) ?: new InfrastructureReport();

            $report
                ->setDate(new DateTimeImmutable('@' . ($fioReportItem->TimestampMs / 1000)))
                ->setSimulationPeriod($fioReportItem->SimulationPeriod)
                ->setIsExplorersGraceEnabled($fioReportItem->ExplorersGraceEnabled)
                ->setPlanet($planet);

            $popr = $report->getPopulationReport() ?: new Planet\PopulationReport();
            $popr
                ->setNeedFulfillmentLifeSupport($fioReportItem->NeedFulfillmentLifeSupport)
                ->setNeedFulfillmentHealth($fioReportItem->NeedFulfillmentHealth)
                ->setNeedFulfillmentSafety($fioReportItem->NeedFulfillmentSafety)
                ->setNeedFulfillmentComfort($fioReportItem->NeedFulfillmentComfort)
                ->setNeedFulfillmentCulture($fioReportItem->NeedFulfillmentCulture)
                ->setNeedFulfillmentEducation($fioReportItem->NeedFulfillmentEducation);

            $report->setPopulationReport($popr);

            $this->persistEntity($report);
            $this->persistEntity($popr);
        }

        $this->flushEntities();

        return Command::SUCCESS;
    }
}
