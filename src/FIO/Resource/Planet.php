<?php

namespace App\FIO\Resource;

final class Planet
{
    public ?string $PlanetId = null;
    public ?string $PlanetNaturalId = null;
    public ?string $PlanetName = null;
    public ?string $Namer = null;
    public ?int $NamingDataEpochMs = null;
    public ?bool $Nameable = null;
    public ?string $SystemId = null;
    public ?float $Gravity = null;
    public ?float $MagneticField = null;
    public ?float $Mass = null;
    public ?float $MassEarth = null;
    public ?float $OrbitSemiMajorAxis = null;
    public ?float $OrbitEccentricity = null;
    public ?float $OrbitInclination = null;
    public ?float $OrbitRightAscension = null;
    public ?float $OrbitPeriapsis = null;
    public ?int $OrbitIndex = null;
    public ?float $Pressure = null;
    public ?float $Radiation = null;
    public ?float $Radius = null;
    public ?float $Sunlight = null;
    public ?bool $Surface = null;
    public ?float $Temperature = null;
    public ?float $Fertility = null;
    public ?bool $HasLocalMarket = null;
    public ?bool $HasChamberOfCommerce = null;
    public ?bool $HasWarehouse = null;
    public ?bool $HasAdministrationCenter = null;
    public ?bool $HasShipyard = null;
    public ?string $FactionCode = null;
    public ?string $FactionName = null;
    public ?string $GoverningEntity = null;
    public ?string $CurrencyName = null;
    public ?string $CurrencyCode = null;
    public ?float $BaseLocalMarketFee = null;
    public ?float $LocalMarketFeeFactor = null;
    public ?float $WarehouseFee = null;
    public ?float $EstablishmentFee = null;
    public ?string $PopulationId = null;
    public ?string $COGCProgramStatus = null;
    public ?int $PlanetTier = null;
    public ?string $Timestamp = null;

    /**
     * @var list<array{MaterialId: string, ResourceType: "GASEOUS"|"LIQUID"|"MINERAL", Factor: float}>
     */
    public array $Resources = [];
    public array $ProductionFees = [];

    /**
     * @var list<array{ProgramType: string, StartEpochMs: int, EndEpochMs: int}>
     */
    public array $COGCPrograms = [];
}