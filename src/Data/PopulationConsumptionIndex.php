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

    // pioneers

    public float $DW = 0;

    public float $RAT = 0;

    public float $OVE = 0;

    public float $COF = 0;

    public float $PWO = 0;

    // settlers

    public float $EXO = 0;

    public float $PT = 0;

    public float $KOM = 0;

    public float $REP = 0;

    // technicians

    public float $MED = 0;

    public float $HMS = 0;

    public float $SCN = 0;

    public float $ALE = 0;

    public float $SC = 0;

    // engineers

    public float $FIM = 0;

    public float $HSS = 0;

    public float $PDA = 0;

    public float $GIN = 0;

    public float $VG = 0;

    // scientists

    public float $MEA = 0;

    public float $LC = 0;

    public float $WS = 0;

    public float $WIN = 0;

    public float $NST = 0;
}