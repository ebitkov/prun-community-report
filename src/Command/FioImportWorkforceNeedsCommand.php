<?php

namespace App\Command;

use App\Autoload\DoctrineAware;
use App\Entity\Material;
use App\Entity\Workforce;
use App\FIO\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'fio:import:workforce-needs',
)]
class FioImportWorkforceNeedsCommand extends Command
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
        $io->info('Importing Workforce Needs');

        $io->write('Getting data from FIO...');
        $needs = $this->fio->getWorkforceNeeds();
        $io->writeln(' done');
        $io->writeln('');

        $total = $needs->count();
        $io->progressStart($total);
        foreach ($needs as $workforceNeed) {
            $workforce = $this->findEntityBy(
                Workforce::class,
                ['type' => $workforceNeed->WorkforceType]
            ) ?: new Workforce();

            $workforce->setType($workforceNeed->WorkforceType);

            // Reset Workforce Needs
            foreach ($workforce->getNeeds() as $need) {
                $workforce->removeNeed($need);
                $this->removeEntity($need);
            }

            foreach ($workforceNeed->Needs as $need) {
                $material = $this->findEntityBy(Material::class, ['fioId' => $need['MaterialId']]);

                $_need = (new Workforce\Need())
                    ->setMaterial($material)
                    ->setWorkforce($workforce)
                    ->setAmount($need['Amount']);

                $this->persistEntity($_need);
            }

            $this->persistEntity($workforce);

            $io->progressAdvance();
        }
        $io->progressFinish();

        $this->flushEntities();

        $io->success('Update Completed');

        return Command::SUCCESS;
    }
}
