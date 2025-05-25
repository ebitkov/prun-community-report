<?php

namespace App\Command;

use App\Entity\Material;
use App\Entity\Material\Category;
use App\FIO\Client;
use App\Repository\Material\CategoryRepository;
use App\Repository\MaterialRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    name: 'fio:import:materials',
    description: 'Imports all material data from the FIO API.',
)]
class FioImportMaterialsCommand extends Command
{
    public function __construct(
        private readonly Client $fio,
        private readonly MaterialRepository $materialRepository,
        private readonly CategoryRepository $categoryRepository,
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Importing Materials');

        $io->write('Collecting data from FIO...');

        $materials = $this->fio->getMaterials();
        $total = $materials->count();
        $io->writeln(' done');
        $io->writeln('');

        $io->progressStart($total);
        foreach ($materials as $i => $material) {
            $entity = $this->materialRepository->findOneBy(['fioId' => $material->MaterialId]) ?? new Material();
            $category = $this->categoryRepository->findOneBy(['fioId' => $material->CategoryId]) ?? new Category();

            $entity->setFioId($material->MaterialId);
            $entity->setName($material->Name);
            $entity->setTicker($material->Ticker);
            $entity->setMass($material->Weight);
            $entity->setVolume($material->Volume);

            $category->setFioId($material->CategoryId);
            $category->setName($material->CategoryName);
            $entity->setCategory($category);

            $this->materialRepository->persist($entity);
            $this->categoryRepository->persist($category, true);

            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success($total . ' materials imported!');

        return Command::SUCCESS;
    }
}
