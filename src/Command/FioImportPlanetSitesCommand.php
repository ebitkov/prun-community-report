<?php

namespace App\Command;

use App\Autoload\DoctrineAware;
use App\Entity\Company;
use App\Entity\Planet;
use App\Entity\Planet\Site;
use App\FIO\Client;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fio:import:planet-sites',
)]
class FioImportPlanetSitesCommand extends Command
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $planetNaturalId = $input->getArgument('natural-id');

        $planet = $this->findEntityBy(Planet::class, ['naturalId' => $planetNaturalId]);
        if (!$planet) {
            $io->error("Planet with id $planetNaturalId not found");
            return Command::FAILURE;
        }

        $io->info("Importing Planet Sites of $planetNaturalId from FIO");

        try {
            $io->write('Reading planet data from FIO...');
            $fioSites = $this->fio->getPlanetSites($planetNaturalId);
            $io->writeln(' done');
            $io->writeln('');
        } catch (Exception) {
            $io->error("No data for planet $planetNaturalId available!");
            return Command::FAILURE;
        }

        foreach ($fioSites as $fioSite) {
            $site = $this->findEntityBy(Site::class, ['siteId' => $fioSite->SiteId]) ?: new Site();

            $site
                ->setSiteId($fioSite->SiteId)
                ->setPlotId($fioSite->PlotId)
                ->setPlotNumber($fioSite->PlotNumber)
                ->setPlanet($planet);

            if (null !== $fioSite->OwnerCode) {
                $company = $this->doctrine
                    ->getRepository(Company::class)
                    ->findOneBy(['fioId' => $fioSite->OwnerId])
                    ?: new Company();

                $company
                    ->setFioId($fioSite->OwnerId)
                    ->setName($fioSite->OwnerName)
                    ->setCode($fioSite->OwnerCode);

                $site->setOwner($company);

                $this->persistEntity($company);;
            }

            $this->persistEntity($site);
        }

        $this->flushEntities();

        return Command::SUCCESS;
    }
}
