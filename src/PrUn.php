<?php

namespace App;

use App\Entity\Planet;

final class PrUn
{
    public const MARKETS = [
        'antares' => 'AI1',
        'arclight' => 'CI2',
        'benten' => 'CI1',
        'hortus' => 'IC1',
        'hubur' => 'NC2',
        'moria' => 'NC1',
    ];

    public const STATIONS = [
        'antares' => 'ANT',
        'arclight' => 'ARC',
        'benten' => 'BEN',
        'hortus' => 'HOR',
        'hubur' => 'HUB',
        'moria' => 'MOR',
    ];

    public const INFRASTRUCTURE = [
        Planet::INFRASTRUCTURE_ADMINISTRATION_CENTER => [
            'icon' => 'fa6-solid:building-columns',
        ],
        Planet::INFRASTRUCTURE_CHAMBER_OF_GLOBAL_COMMERCE => [
            'icon' => 'fa6-solid:gears',
        ],
        Planet::INFRASTRUCTURE_WAREHOUSE => [
            'icon' => 'fa6-solid:warehouse'
        ],
        Planet::INFRASTRUCTURE_LOCAL_MARKET => [
            'icon' => 'fa6-solid:store'
        ],
        Planet::INFRASTRUCTURE_SHIPYARD => [
            'icon' => 'fa6-solid:rocket'
        ]
    ];

    public const COGC_PROGRAM = [
        'ADVERTISING_AGRICULTURE' => [
            'label' => 'Agriculture',
            'icon' => 'fa6-solid:wheat-awn'
        ],
        'ADVERTISING_CHEMISTRY' => [
            'label' => 'Chemistry',
            'icon' => 'fa6-solid:flask',
        ],
        'ADVERTISING_CONSTRUCTION' => [
            'label' => 'Construction',
            'icon' => 'fa6-solid:screwdriver-wrench',
        ],
        'ADVERTISING_ELECTRONICS' => [
            'label' => 'Electronics',
            'icon' => 'fa6-solid:microchip',
        ],
        'ADVERTISING_FOOD_INDUSTRIES' => [
            'label' => 'Food Industries',
            'icon' => 'fa6-solid:bowl-food',
        ],
        'ADVERTISING_FUEL_REFINING' => [
            'label' => 'Fuel Refining',
            'icon' => 'fa6-solid:gas-pump',
        ],
        'ADVERTISING_MANUFACTURING' => [
            'label' => 'Manufacturing',
            'icon' => 'fa6-solid:gears',
        ],
        'ADVERTISING_METALLURGY' => [
            'label' => 'Metallurgy',
            'icon' => 'fa6-solid:fire'
        ],
        'ADVERTISING_RESOURCE_EXTRACTION' => [
            'label' => 'Resource Extraction',
            'icon' => 'fa6-solid:gem'
        ],
        'Pioneers',
        'Settlers',
        'Technicians',
        'Engineers',
        'Scientists',
    ];
}