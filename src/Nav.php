<?php

namespace App;

final class Nav
{
    public array $menus = [
        'main' => [
            'top' => [
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'route' => 'app_home',
                    ],
                ]
            ],
            'reports' => [
                'label' => 'Monthly Economic Reports',
                'items' => [
                    [
                        'label' => 'Antares',
                        'route' => 'app_report_dashboard',
                        'route_parameter' => [
                            'region' => 'antares'
                        ]
                    ]
                ]
            ],
            'data' => [
                'label' => 'Data & Statistics',
                'items' => [
                    [
                        'label' => 'Planets',
                        'route' => 'app_planets',
                    ]
                ]
            ]
        ],
        'report' => [
            'top' => [
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'route' => 'app_home',
                    ],
                ]
            ],
            'anchors' => [
                'label' => 'Regional Report',
                'items' => [
                    [
                        'label' => 'Planets',
                        'anchor' => 'planets',
                    ],
                    [
                        'label' => 'Demography',
                        'anchor' => 'demography',
                    ],
                    [
                        'label' => 'Workforce Costs',
                        'anchor' => 'workforce-costs',
                    ]
                ],
            ],
            'other_reports' => [
                'label' => 'Other Regions',
                'items' => [
                    [
                        'label' => 'Antares',
                        'route' => 'app_report_dashboard',
                        'route_parameter' => [
                            'region' => 'antares'
                        ]
                    ],
                    [
                        'label' => 'Arclight',
                        'route' => 'app_report_dashboard',
                        'route_parameter' => [
                            'region' => 'arclight'
                        ]
                    ],
                    [
                        'label' => 'Benten',
                        'route' => 'app_report_dashboard',
                        'route_parameter' => [
                            'region' => 'benten'
                        ]
                    ],
                    [
                        'label' => 'Hortus',
                        'route' => 'app_report_dashboard',
                        'route_parameter' => [
                            'region' => 'hortus'
                        ]
                    ],
                    [
                        'label' => 'Hubur',
                        'route' => 'app_report_dashboard',
                        'route_parameter' => [
                            'region' => 'hubur'
                        ]
                    ],
                    [
                        'label' => 'Moria',
                        'route' => 'app_report_dashboard',
                        'route_parameter' => [
                            'region' => 'moria'
                        ]
                    ],
                ]
            ]
        ],
        'planetReport' => [
            [
                'items' => [
                    [
                        'label' => 'Back to List',
                        'route' => 'app_planets',
                        'icon' => 'fa6-solid:chevron-left'
                    ],
                ]
            ],
            [
                'items' => [
                    [
                        'label' => 'Overview',
                        'route' => 'app_planets',
                    ],
                    [
                        'label' => 'Population',
                        'route' => 'app_home', # todo
                    ],
                ]
            ]
        ]
    ];

    public function getMenu(string $menuName): array
    {
        return $this->menus[$menuName] ?? [];
    }
}
