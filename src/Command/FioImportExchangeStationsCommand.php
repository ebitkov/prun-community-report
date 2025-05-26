<?php

namespace App\Command;

use App\Autoload\DoctrineAware;
use App\Entity\ExchangeStation;
use App\Entity\System;
use App\FIO\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fio:import:exchange-stations',
    description: 'Add a short description for your command',
)]
class FioImportExchangeStationsCommand extends Command
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
        $io->info('Importing Commodity Exchange Stations');

        $io->write('Reading data from FIO...');
        $fioStations = $this->fio->getExchangeStations();
        $io->writeln(' done');
        $io->writeln('');

        foreach ($fioStations as $fioStation) {
            $station = $this->findEntityBy(
                ExchangeStation::class,
                [
                    'fioId' => $fioStation->StationId
                ]
            ) ?: new ExchangeStation();

            $station
                ->setFioId($fioStation->StationId)
                ->setName($fioStation->Name)
                ->setNaturalId($fioStation->NaturalId)
                ->setSystem($this->findEntityBy(System::class, ['fioId' => $fioStation->SystemId]));

            $this->persistEntity($station);
        }

        $this->flushEntities();

        $io->success('Commodity Exchange Stations have been imported.');

        return Command::SUCCESS;
    }
}
