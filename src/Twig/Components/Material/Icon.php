<?php

namespace App\Twig\Components\Material;

use App\Entity\Material;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('material:icon')]
final class Icon
{
    public ?Material $material = null;

    public string $size = 'md';
}