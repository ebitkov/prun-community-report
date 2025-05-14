<?php

namespace App\Twig\Components\Nav;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('nav:button-bar')]
final class ButtonBar
{
    public string $menu;

    public array $routeParameters = [];
}