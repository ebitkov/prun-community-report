<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('nav')]
final class Nav
{
    public string $menu;

    public array $routeParameters = [];
}
