<?php

namespace App\FIO\Resource\Infrastructure;

final class Project
{
    public array $UpgradeCosts = [];
    public array $Upkeeps = [];
    public array $Contributions = [];
    public ?string $InfraProjectId = null;
    public ?int $SimulationPeriod = null;
    public ?string $Type = null;
    public ?string $Ticker = null;
    public ?string $Name = null;
    public ?int $Level = null;
    public ?int $ActiveLevel = null;
    public ?int $CurrentLevel = null;
    public ?int $UpkeepStatus = null;
    public ?int $UpgradeStatus = null;
}