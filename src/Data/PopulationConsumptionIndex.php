<?php

namespace App\Data;

use DateTimeInterface;

final class PopulationConsumptionIndex
{
    public DateTimeInterface $dateEpoch;

    public float $pioneer;
    public float $settler;
    public float $technician;
    public float $engineer;
    public float $scientist;
}