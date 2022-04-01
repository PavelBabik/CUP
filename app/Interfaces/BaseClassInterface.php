<?php

namespace App\Interfaces;

interface BaseClassInterface
{
    public function findOneAction(int $id);
    public function findAllAction(int $limit, int $offset, string $sort_row, string $sort_direction): array|null;
    public function findAllWhereAction(array $params, int $limit, int $offset, string $sort_row, string $sort_direction): array|null;
    public function deleteAction(int $id): void;
    public function createAction(array $payload): void;
}