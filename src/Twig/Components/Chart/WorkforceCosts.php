<?php

namespace App\Twig\Components\Chart;

use App\Bootstrap;
use App\Data\PopulationConsumptionIndex;
use App\Entity\Workforce;
use App\PrUn;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
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


    private ?EntityManagerInterface $entityManager;


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
        private readonly HttpClientInterface $fioExtensionClient,
        private readonly SerializerInterface $serializer,
        private readonly ChartBuilderInterface $chartBuilder,
        ManagerRegistry $doctrine,
    ) {
        $this->entityManager = $doctrine->getManagerForClass(Workforce::class);
    }


    /**
     * @throws Exception
     */
    public function getChart(): Chart
    {
        if ($this->show === 'all') {
            return $this->getChartOfAll();
        } else {
            return $this->getChartOf($this->show);
        }
    }

    /**
     * @return ArrayCollection<int, PopulationConsumptionIndex>
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    private function getData(): ArrayCollection
    {
        // Get Consumption Index
        $market = PrUn::MARKETS[$this->region];
        $csv = $this->fioExtensionClient->request('GET', "csv/population-consumption-index/$market.csv")->getContent();


        $arr = $this->serializer->deserialize($csv, PopulationConsumptionIndex::class . '[]', 'csv', [
            DateTimeNormalizer::FORMAT_KEY => 'U'
        ]);
        $coll = new ArrayCollection($arr);

        // Filter: Only data of the requested month
        list ($start, $end) = $this->getTimeWindow();
        return $coll->filter(function (PopulationConsumptionIndex $item) use ($start, $end) {
            return $item->dateEpoch >= $start && $item->dateEpoch <= $end;
        });
    }

    private function getChartOfAll(): Chart
    {
        list($start, $end) = $this->getTimeWindow();
        $data = $this->getData();

        $labels = [];
        for ($i = 1; $i <= $end->format('d'); $i++) {
            $labels[] = "$i.";
        }

        $datasets = [];

        foreach ($this->dataPoints as $i => $point) {
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
                'borderColor' => Bootstrap::COLORS[$this->colors[$i]]['hex'],
                'backgroundColor' => Bootstrap::COLORS[$this->colors[$i]]['hex'],
            ];
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

    private function getChartOf(string $workerTier): Chart
    {
        list ($start, $end) = $this->getTimeWindow();
        $data = array_values($this->getData()->toArray());

        $rep = $this->entityManager->getRepository(Workforce::class);
        $workerData = $rep->findOneBy(['type' => strtoupper($workerTier)]);

        $labels = [];
        for ($i = 1; $i <= $end->format('d'); $i++) {
            $labels[] = "$i.";
        }

        $baseColor = Bootstrap::COLORS[$this->colors[array_search($workerTier, $this->dataPoints)]]['rgb'];

        $datasets = [];

        $needs = $workerData->getNeeds()->toArray();
        usort($needs, function ($a, $b) {
            return $b->getAmount() <=> $a->getAmount();
        });

        foreach ($needs as $i => $need) {
            $color = Bootstrap::rgbToHex(Bootstrap::tintColor($baseColor, $i * 0.25 - 0.75));

            $ticker = $need->getMaterial()->getTicker();
            $amount = $need->getAmount() / 100; // per worker
            $_set = [];
            foreach ($data as $item) {
                $price = $item->$ticker;
                $_set[] = $price * $amount;
            }
            $datasets[] = [
                'label' => $ticker,
                'data' => $_set,
                'backgroundColor' => $color,
                'borderColor' => 'white',
                'borderWidth' => 1,
            ];
        }

        $chart = $this->chartBuilder->createChart('bar');
        $chart->setData([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
        $chart->setOptions([
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                ]
            ]
        ]);

        return $chart;
    }

    /**
     * @throws Exception
     */
    private function getTimeWindow(): array
    {
        return [
            new DateTime($this->year . '-' . $this->month . '-01'),
            (new DateTime($this->year . '-' . $this->month . '-01'))
                ->modify('last day of this month')
        ];
    }


    public function getBounds(): array
    {
        return $this->bounds;
    }
}
