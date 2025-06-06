<?php

namespace App;

final class Bootstrap
{
    public const COLORS = [
        'blue' => [
            'hex' => '#0d6efd',
            'rgb' => [13, 110, 253],
        ],
        'indigo' => [
            'hex' => '#6610f2',
            'rgb' => [102, 16, 242],
        ],
        'purple' => [
            'hex' => '#6f42c1',
            'rgb' => [111, 66, 193],
        ],
        'pink' => [
            'hex' => '#d63384',
            'rgb' => [214, 51, 132],
        ],
        'red' => [
            'hex' => '#dc3545',
            'rgb' => [220, 53, 69],
        ],
        'orange' => [
            'hex' => '#fd7e14',
            'rgb' => [253, 126, 20],
        ],
        'yellow' => [
            'hex' => '#ffc107',
            'rgb' => [255, 193, 7],
        ],
        'green' => [
            'hex' => '#198754',
            'rgb' => [25, 135, 84],
        ],
        'teal' => [
            'hex' => '#20c997',
            'rgb' => [32, 201, 151],
        ],
        'cyan' => [
            'hex' => '#0dcaf0',
            'rgb' => [13, 202, 240],
        ],
        'gray' => [
            'hex' => '#adb5bd',
        ],
    ];

    public static function rgbToHex(array $rgb): string
    {
        if (count($rgb) !== 3) {
            throw new \InvalidArgumentException('RGB-Array muss genau 3 Elemente enthalten.');
        }

        // Werte validieren
        foreach ($rgb as $channel) {
            if (!is_int($channel) || $channel < 0 || $channel > 255) {
                throw new \InvalidArgumentException('RGB-Werte müssen ganze Zahlen zwischen 0 und 255 sein.');
            }
        }

        // Mit sprintf zu Hex formatieren und führende Nullen erzwingen
        return sprintf('#%02x%02x%02x', ...$rgb);
    }

    public static function tintColor(array $rgb, float $percent): array
    {
        return array_map(function ($channel) use ($percent) {
            return intval(min($channel * (1+$percent), 255));
        }, $rgb);
    }
}