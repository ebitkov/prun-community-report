<?php

namespace App\Command;

use App\FIO\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Symfony\Component\Console\Output\OutputInterface;
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
        $this->bus->dispatch(new RunCommandMessage('fio:import:materials'));
        $this->bus->dispatch(new RunCommandMessage('fio:import:buildings'));
        $this->bus->dispatch(new RunCommandMessage('fio:import:workforce-needs'));
        $this->bus->dispatch(new RunCommandMessage('fio:import:systems'));

        foreach ($this->fio->getPlanets() as $planet) {
            $naturalId = $planet->PlanetNaturalId;
            $this->bus->dispatch(new RunCommandMessage("fio:import:planet $naturalId"));
            $this->bus->dispatch(new RunCommandMessage("fio:import:planet-sites $naturalId"));
            $this->bus->dispatch(new RunCommandMessage("fio:import:infrastructure-report $naturalId"));
        }

        return Command::SUCCESS;
    }
}
