<?php

namespace App\FIO\Resource\Global;

final class WorkforceNeed
{
    public ?string $WorkforceType = null;

    /** @var list<array{ MaterialId: string, MaterialName: string, MaterialTicker: string, MaterialCategory: string, Amount: float }> */
    public array $Needs = [];
}