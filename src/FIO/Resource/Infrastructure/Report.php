<?php

namespace App\FIO\Resource\Infrastructure;

final class Report
{
    public ?string $InfrastructureReportId = null;

    public ?bool $ExplorersGraceEnabled = null;

    public ?int $SimulationPeriod = null;
    public ?int $TimestampMs = null;

    public ?int $NextPopulationPioneer = null;
    public ?int $NextPopulationSettler = null;
    public ?int $NextPopulationTechnician = null;
    public ?int $NextPopulationEngineer = null;
    public ?int $NextPopulationScientist = null;

    public ?int $PopulationDifferencePioneer = null;
    public ?int $PopulationDifferenceSettler = null;
    public ?int $PopulationDifferenceTechnician = null;
    public ?int $PopulationDifferenceEngineer = null;
    public ?int $PopulationDifferenceScientist = null;

    public ?float $AverageHappinessPioneer = null;
    public ?float $AverageHappinessSettler = null;
    public ?float $AverageHappinessTechnician = null;
    public ?float $AverageHappinessEngineer = null;
    public ?float $AverageHappinessScientist = null;

    public ?float $UnemploymentRatePioneer = null;
    public ?float $UnemploymentRateSettler = null;
    public ?float $UnemploymentRateTechnician = null;
    public ?float $UnemploymentRateEngineer = null;
    public ?float $UnemploymentRateScientist = null;

    public ?int $OpenJobsPioneer = null;
    public ?int $OpenJobsSettler = null;
    public ?int $OpenJobsTechnician = null;
    public ?int $OpenJobsEngineer = null;
    public ?int $OpenJobsScientist = null;

    public ?float $NeedFulfillmentLifeSupport = null;
    public ?float $NeedFulfillmentSafety = null;
    public ?float $NeedFulfillmentHealth = null;
    public ?float $NeedFulfillmentComfort = null;
    public ?float $NeedFulfillmentCulture = null;
    public ?float $NeedFulfillmentEducation = null;
}