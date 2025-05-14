<?php

namespace App\Twig\Components\Nav;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('nav:sidebar')]
final class Sidebar
{
    public string $menu;

    public array $routeParameters = [];
}
