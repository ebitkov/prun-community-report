<?php

namespace App\Twig\Components\Chart;

use App\Bootstrap;
use App\Data\PopulationConsumptionIndex;
use App\PrUn;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

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


    private array $dataPoints = ['pioneer', 'settler', 'technician', 'engineer', 'scientist'];
    private array $colors = ['blue', 'purple', 'red', 'orange', 'green'];

    #[ExposeInTemplate]
    private array $bounds = [];


    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ChartBuilderInterface $chartBuilder,
    ) {
    }


    /**
     * @throws Exception
     */
    public function getChart(): Chart
    {
        // Get Consumption Index
        $market = PrUn::MARKETS[$this->region];
        $csv = file_get_contents(
            "https://raw.githubusercontent.com/ebitkov/fio-extension/refs/heads/main/" .
            "csv/population-consumption-index/$market.csv"
        );

        $end = new DateTime($this->year . '-' . $this->month . '-01');
        $start = new DateTime($this->year . '-' . $this->month . '-01');
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

        foreach ($this->dataPoints as $i => $point) {
            if (in_array($this->show, ['all', $point])) {
                $set = array_values(
                    $data->map(function (PopulationConsumptionIndex $item) use ($point) {
                        return round($item->$point / 100, 2);
                    })->toArray()
                );
                $this->bounds[$point] = [
                    'start' => $set[0],
                    'end' => $set[count($set) - 1],
                ];
                $datasets[] = [
                    'label' => ucfirst($point) . 's',
                    'data' => $set,
                    'borderColor' => Bootstrap::COLORS[$this->colors[$i]],
                    'backgroundColor' => Bootstrap::COLORS[$this->colors[$i]],
                ];
            }
        }

        $chart = $this->chartBuilder->createChart('line');
        $chart->setData([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
        $chart->setOptions([
            'plugins' => [
                'legend' => [
                    'display' => false,
                ]
            ]
        ]);

        return $chart;
    }


    public function getBounds(): array
    {
        return $this->bounds;
    }
}
