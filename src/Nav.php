<?php

namespace App;

final class Nav
{
    public array $menus = [
        'main' => [
            'top' => [
                'items' => [
                    [
                        'label' => 'Planets',
                        'route' => 'app_planets',
                    ]
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
            ]
        ],
        'planetReport' => [
            [
                'items' => [
                    [
                        'label' => 'Back to List',
                        'route' => 'app_home', # todo
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