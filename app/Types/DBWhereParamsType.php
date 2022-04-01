<?php

namespace App\Types;

use App\Interfaces\TypeInterface;

class DBWhereParamsType extends BaseType implements TypeInterface
{
    public int $limit = 50;
    public int $offset = 0;
    public string $sort_row = 'id';
    public string $sort_direction = 'DESC';
    public string $select = '*';
    public array $where = [];
    public bool $ignore_deleted = false;
}