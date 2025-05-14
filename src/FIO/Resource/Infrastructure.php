<?php

namespace App\FIO\Resource;

use App\FIO\Resource\Infrastructure\Program;
use App\FIO\Resource\Infrastructure\Project;
use App\FIO\Resource\Infrastructure\Report;
use DateTimeImmutable;

final class Infrastructure
{
    public ?string $InfrastructureId = null;

    public ?DateTimeImmutable $Timestamp = null;

    /** @var Project[] */
    public array $InfrastructureProjects = [];

    /** @var Report[] */
    public array $InfrastructureReports = [];

    /** @var Program[] */
    public array $InfrastructurePrograms = [];
}