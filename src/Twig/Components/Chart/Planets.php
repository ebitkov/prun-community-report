<?php

namespace App\Twig\Components\Chart;

use App\Bootstrap;
use App\Repository\PlanetRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('chart:planets')]
final class Planets
{
    public string $region;


    public function __construct(
        private readonly ChartBuilderInterface $chartBuilder,
        private readonly PlanetRepository $planetRepository,
    ) {
    }

    public function getChart(): Chart
    {
        $chart = $this->chartBuilder->createChart('doughnut');

        // Total Planets Count
        $total = $this->planetRepository
            ->createQueryBuilder('planet')
            ->select('count(planet.id)')
            ->where('planet.jumpsToAnt <= 5')
            ->getQuery()->getSingleScalarResult();

        // Inhabited Planets (at least one player base)
        $_qb = $this->planetRepository
            ->createQueryBuilder('planet')
            ->select('planet.id, count(site.id)')
            ->join('planet.sites', 'site')
            ->where('site.owner IS NOT NULL')
            ->andWhere('planet.jumpsToAnt <= 5')
            ->groupBy('planet');

        $inhabited = count($_qb->getQuery()->getResult());

        $chart->setData([
            'labels' => [
                'Colonized',
                'Uncolonized',
            ],
            'datasets' => [
                [
                    'data' => [
                        $inhabited,
                        $total - $inhabited,
                    ],
                    'backgroundColor' => [
                        Bootstrap::COLORS['blue']['hex'],
                        Bootstrap::COLORS['gray']['hex'],
                    ]
                ],
            ]
        ]);
        $chart->setOptions([
            'maintainAspectRatio' => false,
        ]);

        return $chart;
    }
}
