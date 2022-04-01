<?php

namespace App\Interfaces;

use App\Entities\CityEntity;
use App\Entities\ProfileEntity;
use App\Types\DBWhereParamsType;

interface ClassInterface
{
    public function findByPrimary(int|string $primary, DBWhereParamsType $DBWhereParamsType = null);
    public function findOne(DBWhereParamsType $DBWhereParamsType);
    public function findAll(DBWhereParamsType $DBWhereParamsType): array|null;
    public function delete(int|string $primary, string $row_name = ''): void;
    public function create(array $payload): int|bool;
    public function edit(array $payload): bool;
    public function countAll(): int;
    public function countWhere(DBWhereParamsType $DBWhereParamsType): int;
}