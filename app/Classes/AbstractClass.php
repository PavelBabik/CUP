<?php

namespace App\Classes;

use App\Interfaces\ClassInterface;
use CodeIgniter\Model;

abstract class AbstractClass implements ClassInterface
{
    public Model $model;
}