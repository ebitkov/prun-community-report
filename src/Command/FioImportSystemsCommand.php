<?php

namespace App\Command;

use App\Autoload\DoctrineAware;
use App\Entity\System;
use App\FIO\Client;
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
    name: 'fio:import:systems',
)]
class FioImportSystemsCommand extends Command
{
    use DoctrineAware;


    public function __construct(
        private readonly Client $fio,
    ) {
        parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Importing Star Systems from FIO');

        $io->write('Reading data from FIO...');
        $fioSystems = $this->fio->getSystemStars();
        $io->writeln(' done');
        $io->writeln('');

        $total = $fioSystems->count();
        $io->progressStart($total);
        foreach ($fioSystems as $fioSystem) {
            $system = $this->findEntityBy(System::class, ['fioId' => $fioSystem->SystemId]) ?: new System();

            $system
                ->setFioId($fioSystem->SystemId)
                ->setNaturalId($fioSystem->NaturalId)
                ->setName($fioSystem->Name);

            $this->persistEntity($system);
            $io->progressAdvance();
        }
        $io->progressFinish();

        $this->flushEntities();

        $io->success("$total systems updated");

        return Command::SUCCESS;
    }
}
