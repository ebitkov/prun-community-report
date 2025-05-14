<?php

namespace App\FIO\Resource\Infrastructure;

final class Program
{
    public ?int $Number = null;
    public ?int $StartTimestampEpochMs = null;
    public ?int $EndTimestampEpochMs = null;
    public ?string $Category = null;
    public ?string $Program = null;
}