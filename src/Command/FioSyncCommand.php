<?php

namespace App\Command;

use App\FIO\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
    private const CX_SYSTEMS = [
        'antares' => 'ZV-307',
        'benten' => 'UV-351',
        'arclight' => 'AM-783',
        'moria' => 'OT-580',
        'hortus' => 'VH-331',
        'hubur' => 'TD-203',
    ];

    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly Client $fio,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'region',
            'r',
            InputOption::VALUE_OPTIONAL,
            'Filters the planets by region. This takes a bit longer, since it filters the planets by distance to the regional CX. '.
            'Possible values are: antares, arclight, benten, hortus, hubur, moria'
        );
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
        $region = $input->getOption('region');

        if (!in_array($region, array_keys(self::CX_SYSTEMS))) {
            $io->error("Invalid region '$region'");
            return Command::FAILURE;
        }

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
        $io->writeln("Queuing Planet Imports" . ($region ? " for $region" : ''));

        $planets = $this->fio->getPlanets();
        $io->progressStart($planets->count());
        foreach ($planets as $planet) {
            $naturalId = $planet->PlanetNaturalId;

            $distanceToCx = null;
            if ($region) {
                $distanceToCx = $this->fio->getJumpCount($naturalId, self::CX_SYSTEMS[$region]);;
            }

            if ($distanceToCx && $distanceToCx <= 5) {
                $this->bus->dispatch(new RunCommandMessage("fio:import:planet $naturalId"));
                $this->bus->dispatch(new RunCommandMessage("fio:import:planet-sites $naturalId"));
                $this->bus->dispatch(new RunCommandMessage("fio:import:infrastructure-report $naturalId"));
            }

            $io->progressAdvance();
        }
        $io->progressFinish();

        $io->success(
            'Queued up all imports.\n' .
            'Run `symfony console messenger:consume command -l 1000` to run the imports.'
        );
        return Command::SUCCESS;
    }
}
