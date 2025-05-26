<?php

namespace App\FIO\Resource;

use DateTimeInterface;

class ExchangeStation
{
    public ?string $StationId = null;
    public ?string $NaturalId = null;
    public ?string $Name = null;
    public ?string $SystemId = null;
    public ?string $SystemNaturalId = null;
    public ?string $SystemName = null;
    public ?int $CommisionTimeEpochMs = null;
    public ?string $ComexId = null;
    public ?string $ComexName = null;
    public ?string $ComexCode = null;
    public ?string $WarehouseId = null;
    public ?string $CountryId = null;
    public ?string $CountryCode = null;
    public ?string $CountryName = null;
    public ?int $CurrencyNumericCode = null;
    public ?string $CurrencyCode = null;
    public ?string $CurrencyName = null;
    public ?int $CurrencyDecimals = null;
    public ?string $UserNameSubmitted = null;
    public ?DateTimeInterface $Timestamp = null;
}