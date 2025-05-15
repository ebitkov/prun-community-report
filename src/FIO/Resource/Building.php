<?php

namespace App\FIO\Resource;

final class Building
{

    /** @var list<array{ CommodityName: string, CommodityTicker: string, Weight: float, Volume: float, Amount: int }> */
    public array $BuildingCosts = [];

    /** @var list<array{ Inputs: array, Outputs: array, BuildingRecipeId: string, DurationMs: int, RecipeName: string, StandardRecipeName: string }> */
    public array $Recipes = [];

    public ?string $BuildingId = null;
    public ?string $Name = null;
    public ?string $Ticker = null;
    public ?string $Expertise = null;
    public ?int $Pioneers = null;
    public ?int $Settlers = null;
    public ?int $Technicians = null;
    public ?int $Engineers = null;
    public ?int $Scientists = null;
    public ?int $AreaCost = null;
}