<?php

namespace App\Twig\Components\Chart;

use App\Bootstrap;
use App\Entity\FIO\CSV\InfrastructureReport;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('chart:population-report')]
final class PopulationReport
{
    public ?InfrastructureReport $report = null;


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

        $data = [
            $this->report->getNextPopulationPioneer(),
            $this->report->getNextPopulationSettler(),
            $this->report->getNextPopulationTechnician(),
            $this->report->getNextPopulationEngineer(),
            $this->report->getNextPopulationScientist(),
        ];

        $chart = $this->chartBuilder->createChart('pie');
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => [
                        Bootstrap::COLORS['blue'],
                        Bootstrap::COLORS['purple'],
                        Bootstrap::COLORS['red'],
                        Bootstrap::COLORS['orange'],
                        Bootstrap::COLORS['green']
                    ]
                ]
            ]
        ]);
        $chart->setOptions([
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom'
                ]
            ]
        ]);

        return $chart;
    }

    public function getTotal(): int
    {
        return
            $this->report->getNextPopulationPioneer() +
            $this->report->getNextPopulationSettler() +
            $this->report->getNextPopulationTechnician() +
            $this->report->getNextPopulationEngineer() +
            $this->report->getNextPopulationScientist();
    }
}
