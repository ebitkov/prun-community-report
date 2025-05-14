<?php

namespace App;

use App\Entity\Planet;

final class PrUn
{
    public const MARKETS = [
        'antares' => 'AI1'
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
        'Construction',
        'ADVERTISING_ELECTRONICS' => [
            'label' => 'Electronics',
            'icon' => 'fa6-solid:microchip',
        ],
        'Food Industries',
        'Fuel Refining',
        'Manufacturing',
        'ADVERTISING_METALLURGY' => [
            'label' => 'Metallurgy',
            'icon' => 'fa6-solid:fire'
        ],
        'Resource Extraction',
        'Pioneers',
        'Settlers',
        'Technicians',
        'Engineers',
        'Scientists',
    ];
}