<?php

namespace App\Twig\Components\Diagram;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent('diagram:range-bars')]
final class RangeBars
{
    public float $min;
    public float $max;
    public float $mid;
    public float $value;

    #[PostMount]
    public function postMount(): void
    {
        $this->mid = ($this->min + $this->max) / 2;
    }
}