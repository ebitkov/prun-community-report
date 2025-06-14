<?php

namespace App\Twig\Components\Chart;

use App\Bootstrap;
use App\Entity\Planet;
use App\PrUn;
use App\Repository\PlanetRepository;
use Exception;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('chart:planets')]
final class Planets
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $view = 'colonization';

    #[LiveProp(writable: true)]
    public bool $includeOnlyColonized = false;

    #[LiveProp]
    public string $region;


    public function __construct(
        private readonly ChartBuilderInterface $chartBuilder,
        private readonly PlanetRepository $planetRepository,
    ) {
    }


    /**
     * @throws Exception
     */
    public function getChart(): Chart
    {
        return match ($this->view) {
            'colonization' => $this->getColonizationChart(),
            'surface' => $this->getSurfaceChart(),
            'temperature' => $this->getTemperatureChart(),
            'gravitation' => $this->getGravityChart(),
            'pressure' => $this->getPressureChart(),
            'fertility' => $this->getFertilityChart(),
            default => throw new Exception('Unknown view'),
        };
    }

    public function getColonizationChart(): Chart
    {
        $chart = $this->createDoughnutChart();

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

        return $chart;
    }

    private function getSurfaceChart(): Chart
    {
        $chart = $this->createDoughnutChart();

        $planets = $this->getPlanets();
        $data = [
            'rocky' => 0,
            'gaseous' => 0,
        ];
        foreach ($planets as $planet) {
            if ($planet->hasSurface()) {
                $data['rocky']++;
            } else {
                $data['gaseous']++;
            }
        }


        $chart->setData([
            'labels' => [
                'Rocky',
                'Gaseous',
            ],
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => [
                        Bootstrap::COLORS['blue']['hex'],
                        Bootstrap::COLORS['purple']['hex'],
                    ]
                ],
            ]
        ]);

        return $chart;
    }

    private function getTemperatureChart(): Chart
    {
        $chart = $this->createDoughnutChart();

        $planets = $this->getPlanets();
        $data = [
            'low' => 0,
            'normal' => 0,
            'high' => 0,
        ];
        foreach ($planets as $planet) {
            if ($planet->getTemperature() < -25) {
                $data['low']++;
            } elseif ($planet->getTemperature() > 75) {
                $data['high']++;
            } else {
                $data['normal']++;
            }
        }


        $chart->setData([
            'labels' => [
                'Low',
                'Normal',
                'High',
            ],
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => [
                        Bootstrap::COLORS['blue']['hex'],
                        Bootstrap::COLORS['purple']['hex'],
                        Bootstrap::COLORS['red']['hex'],
                    ]
                ],
            ]
        ]);

        return $chart;
    }

    private function getGravityChart(): Chart
    {
        $chart = $this->createDoughnutChart();

        $planets = $this->getPlanets();
        $data = [
            'low' => 0,
            'normal' => 0,
            'high' => 0,
        ];
        foreach ($planets as $planet) {
            if ($planet->getGravity() < 0.25) {
                $data['low']++;
            } elseif ($planet->getGravity() > 2.5) {
                $data['high']++;
            } else {
                $data['normal']++;
            }
        }


        $chart->setData([
            'labels' => [
                'Low',
                'Normal',
                'High',
            ],
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => [
                        Bootstrap::COLORS['blue']['hex'],
                        Bootstrap::COLORS['purple']['hex'],
                        Bootstrap::COLORS['red']['hex'],
                    ]
                ],
            ]
        ]);

        return $chart;
    }

    private function getPressureChart(): Chart
    {
        $chart = $this->createDoughnutChart();

        $planets = $this->getPlanets();
        $data = [
            'low' => 0,
            'normal' => 0,
            'high' => 0,
        ];
        foreach ($planets as $planet) {
            if ($planet->getPressure() < 0.25) {
                $data['low']++;
            } elseif ($planet->getPressure() > 2) {
                $data['high']++;
            } else {
                $data['normal']++;
            }
        }


        $chart->setData([
            'labels' => [
                'Low',
                'Normal',
                'High',
            ],
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => [
                        Bootstrap::COLORS['blue']['hex'],
                        Bootstrap::COLORS['purple']['hex'],
                        Bootstrap::COLORS['red']['hex'],
                    ]
                ],
            ]
        ]);

        return $chart;
    }

    private function getFertilityChart(): Chart
    {
        $chart = $this->createDoughnutChart();

        $planets = $this->getPlanets();
        $data = [
            'low' => 0,
            'normal' => 0,
            'high' => 0,
        ];
        foreach ($planets as $planet) {
            if ($planet->isFertile()) {
                $fertility = 1 + $planet->getFertility() * (10 / 33);
                if ($fertility < 0.8) {
                    $data['low']++;
                } elseif ($fertility > 1) {
                    $data['high']++;
                } else {
                    $data['normal']++;
                }
            }
        }


        $chart->setData([
            'labels' => [
                'Low (< 80%)',
                'Normal (80% - 100%)',
                'High (> 100%)',
            ],
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => [
                        Bootstrap::COLORS['blue']['hex'],
                        Bootstrap::COLORS['purple']['hex'],
                        Bootstrap::COLORS['red']['hex'],
                    ]
                ],
            ]
        ]);

        return $chart;
    }

    private function createDoughnutChart(): Chart
    {
        $chart = $this->chartBuilder->createChart('doughnut');

        $chart->setOptions([
            'maintainAspectRatio' => false,
        ]);

        return $chart;
    }

    /**
     * @return Planet[]
     */
    private function getPlanets(): array
    {
        $station = ucfirst(strtolower(PrUn::STATIONS[$this->region]));
        $property = "jumpsTo$station";
        $_qb = $this->planetRepository->createQueryBuilder('planet');
        $_qb->where("planet.$property <= 5");

        if ($this->includeOnlyColonized) {
            $_qb->join('planet.sites', 'site')
                ->addSelect('site')
                ->where('site.owner IS NOT NULL');
        }

        $planets = $_qb->getQuery()->getResult();

        if ($this->includeOnlyColonized) {
            $planets = array_filter($planets, function (Planet $planet) {
                return $planet->getSites()->count() > 0;
            });
        }

        return $planets;
    }
}
