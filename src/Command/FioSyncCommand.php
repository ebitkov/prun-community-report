<?php

namespace App\Command;

use App\FIO\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'fio:sync',
    description: 'Synchronizes the local database with the FIO API.',
)]
class FioSyncCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly Client $fio,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Starting Full Database Synchronization');

        $io->writeln('Queued Material Import');
        $this->bus->dispatch(new RunCommandMessage('fio:import:materials'));

        $io->writeln('Queued Building Import');
        $this->bus->dispatch(new RunCommandMessage('fio:import:buildings'));

        $io->writeln('Queued Workforce Needs Import');
        $this->bus->dispatch(new RunCommandMessage('fio:import:workforce-needs'));

        $io->writeln('Queued System Import');
        $this->bus->dispatch(new RunCommandMessage('fio:import:systems'));

        $io->writeln('Queued Commodity Exchange Station Import');
        $this->bus->dispatch(new RunCommandMessage('fio:import:exchange-stations'));

        $io->writeln('Reading planet list from FIO');
        foreach ($this->fio->getPlanets() as $planet) {
            $naturalId = $planet->PlanetNaturalId;

            $io->writeln("Queued Planet Import for $naturalId");
            $this->bus->dispatch(new RunCommandMessage("fio:import:planet $naturalId"));

            $io->writeln("Queued Planet Sites Import for $naturalId");
            $this->bus->dispatch(new RunCommandMessage("fio:import:planet-sites $naturalId"));

            $io->writeln("Queued Infrastructure Report Import for $naturalId");
            $this->bus->dispatch(new RunCommandMessage("fio:import:infrastructure-report $naturalId"));
        }

        $io->success(
            'Queued up all imports.<br>' .
            'Run `symfony console messenger:consume command -l 1000` to run the imports.'
        );
        return Command::SUCCESS;
    }
}
