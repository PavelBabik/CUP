<?php

namespace App\Interfaces;

interface TypeInterface
{
    public function getPublicData(): array;
    public function getAllData(): array;
}