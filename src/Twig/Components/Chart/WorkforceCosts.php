<?php

namespace App\Twig\Components\Chart;

use App\Bootstrap;
use App\Data\PopulationConsumptionIndex;
use App\PrUn;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('chart:workforce-costs')]
final class WorkforceCosts
{
    use DefaultActionTrait;


    #[LiveProp(writable: true)]
    public string $show = 'all';


    #[LiveProp]
    public int $year;

    #[LiveProp]
    public int $month;

    #[LiveProp]
    public string $region;


    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ChartBuilderInterface $chartBuilder,
    ) {
    }


    public function getChart(): Chart
    {
        // Get Consumption Index
        $market = PrUn::MARKETS[$this->region];
        $csv = file_get_contents(
            "https://raw.githubusercontent.com/ebitkov/fio-extension/refs/heads/main/" .
            "csv/population-consumption-index/$market.csv"
        );

        $end = new \DateTime($this->year . '-' . $this->month . '-01');
        $start = new \DateTime($this->year . '-' . $this->month . '-01');
        $end->modify('last day of this month');


        $arr = $this->serializer->deserialize($csv, PopulationConsumptionIndex::class . '[]', 'csv', [
            DateTimeNormalizer::FORMAT_KEY => 'U'
        ]);
        $coll = new ArrayCollection($arr);

        // Filter: Only data of the requested month
        $data = $coll->filter(function (PopulationConsumptionIndex $item) use ($start, $end) {
            return $item->dateEpoch >= $start && $item->dateEpoch <= $end;
        });

        $labels = [];
        for ($i = 1; $i <= $end->format('d'); $i++) {
            $labels[] = "$i.";
        }

        $datasets = [];

        if (in_array($this->show, ['all', 'pioneer'])) {
            $datasets[] = [
                'label' => 'Pioneers',
                'data' => array_values(
                    $data->map(function (PopulationConsumptionIndex $item) {
                        return round($item->pioneer / 100, 2);
                    })->toArray()
                ),
                'borderColor' => Bootstrap::COLORS['blue'],
                'backgroundColor' => Bootstrap::COLORS['blue'],
            ];
        }
        if (in_array($this->show, ['all', 'settler'])) {
            $datasets[] = [
                'label' => 'Settlers',
                'data' => array_values(
                    $data->map(function (PopulationConsumptionIndex $item) {
                        return round($item->settler / 100, 2);
                    })->toArray()
                ),
                'borderColor' => Bootstrap::COLORS['purple'],
                'backgroundColor' => Bootstrap::COLORS['purple'],
            ];
        }
        if (in_array($this->show, ['all', 'technician'])) {
            $datasets[] = [
                'label' => 'Technicians',
                'data' => array_values(
                    $data->map(function (PopulationConsumptionIndex $item) {
                        return round($item->technician / 100, 2);
                    })->toArray()
                ),
                'borderColor' => Bootstrap::COLORS['red'],
                'backgroundColor' => Bootstrap::COLORS['red'],
            ];
        }
        if (in_array($this->show, ['all', 'engineer'])) {
            $datasets[] = [
                'label' => 'Engineers',
                'data' => array_values(
                    $data->map(function (PopulationConsumptionIndex $item) {
                        return round($item->engineer / 100, 2);
                    })->toArray()
                ),
                'borderColor' => Bootstrap::COLORS['orange'],
                'backgroundColor' => Bootstrap::COLORS['orange'],
            ];
        }
        if (in_array($this->show, ['all', 'scientist'])) {
            $datasets[] = [
                'label' => 'Scientists',
                'data' => array_values(
                    $data->map(function (PopulationConsumptionIndex $item) {
                        return round($item->scientist / 100, 2);
                    })->toArray()
                ),
                'borderColor' => Bootstrap::COLORS['green'],
                'backgroundColor' => Bootstrap::COLORS['green'],
            ];
        }

        $chart = $this->chartBuilder->createChart('line');
        $chart->setData([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
        $chart->setOptions([
            'animation' => [
                'enabled' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ]
            ]
        ]);

        return $chart;
    }
}
