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
        ]
    ];

    public function getMenu(string $menuName): array
    {
        return $this->menus[$menuName] ?? [];
    }
}