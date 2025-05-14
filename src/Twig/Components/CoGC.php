<?php

namespace App\Twig\Components;

use App\Entity\Planet\CoGCProgram;
use App\PrUn;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('cogc')]
final class CoGC
{
    public CoGCProgram $program;

    public function getType(): string
    {
        return $this->program->getType();
    }

    public function getStatus(): string
    {
        return $this->program->getStatus();
    }

    public function getIcon(): string
    {
        return PrUn::COGC_PROGRAM[$this->getType()]['icon'];
    }

    public function getLabel(): string
    {
        return PrUn::COGC_PROGRAM[$this->getType()]['label'];
    }

    public function getStatusColor(): string
    {
        return match ($this->getStatus()) {
            'ACTIVE' => 'success',
            default => 'light'
        };
    }
}
