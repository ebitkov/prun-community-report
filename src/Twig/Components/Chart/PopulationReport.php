<?php

namespace App\Twig\Components\Chart;

use App\Bootstrap;
use App\Entity\Planet;
use App\Entity\Planet\InfrastructureReport;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('chart:population-report')]
final class PopulationReport
{
    public Planet $planet;


    public function __construct(
        private readonly ChartBuilderInterface $chartBuilder,
    ) {
    }


    public function getChart(): Chart
    {
        $labels = [
            'Pioneers',
            'Settlers',
            'Technicians',
            'Engineers',
            'Scientists'
        ];

        $latestReport = $this->getLatestReport();
        $data = [
            $latestReport->getPioneers()->getAmount(),
            $latestReport->getSettlers()->getAmount(),
            $latestReport->getTechnicians()->getAmount(),
            $latestReport->getEngineers()->getAmount(),
            $latestReport->getScientists()->getAmount(),
        ];

        $chart = $this->chartBuilder->createChart('pie');
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => [
                        Bootstrap::COLORS['blue']['hex'],
                        Bootstrap::COLORS['purple']['hex'],
                        Bootstrap::COLORS['red']['hex'],
                        Bootstrap::COLORS['orange']['hex'],
                        Bootstrap::COLORS['green']['hex']
                    ]
                ]
            ]
        ]);
        $chart->setOptions([
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'right'
                ]
            ]
        ]);

        return $chart;
    }

    public function getTotal(): int
    {
        $latest = $this->getLatestReport();
        return
            $latest->getPioneers()->getAmount() +
            $latest->getSettlers()->getAmount() +
            $latest->getTechnicians()->getAmount() +
            $latest->getEngineers()->getAmount() +
            $latest->getScientists()->getAmount();
    }


    public function getLatestReport(): InfrastructureReport
    {
        return $this->planet->getPopulationReports()->last();
    }
}
