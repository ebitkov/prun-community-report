<?php

namespace App;

final class Nav
{
    public array $menus = [
        'main' => [
            'top' => [
                'items' => [
                    'dashboard' => [
                        'icon' => 'fa6-solid:house',
                        'label' => 'Dashboard',
                        'route' => 'app_home',
                    ]
                ]
            ]
        ],
        'report' => [
            [
                'label' => 'Report',
                'items' => [
                    [
                        'label' => 'Overview',
                        'route' => 'app_report_dashboard'
                    ],
                    [
                        'label' => 'Planets',
                        'route' => 'app_home'
                    ],
                    [
                        'label' => 'Markets',
                        'route' => 'app_home'
                    ]
                ]
            ]
        ]
    ];

    public function getMenu(string $menuName): array
    {
        return $this->menus[$menuName] ?? [];
    }
}