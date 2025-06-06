<?php

namespace App\Twig\Components\Nav;

use InvalidArgumentException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('nav:sidebar')]
final class Sidebar
{
    public string $menu;

    public array $routeParameters = [];


    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function getNavItemLink(array $item): string
    {
        if ($item['route'] ?? null) {
            return $this->urlGenerator->generate(
                $item['route'],
                [
                    ...$this->routeParameters,
                    ...$item['route_parameter'] ?? [],
                ]
            );
        }

        if ($item['anchor'] ?? null) {
            return '#' . $item['anchor'];
        }

        throw new InvalidArgumentException('Failed to generate a link for the menu item. Invalid options set.');
    }
}
