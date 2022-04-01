<?php

namespace App\Types;

use App\Interfaces\TypeInterface;
use ReflectionClass;
use ReflectionProperty;

class BaseType
{
    public function __construct(array $payload = [])
    {
        foreach ($payload as $key => $value) {
            if (isset($this->$key) && $value != null) {
                $this->$key = $value;
            }
        }
    }

    public function load(array $payload = [])
    {
        foreach ($payload as $key => $value) {
            if (isset($this->$key) && $value != null) {
                $this->$key = $value;
            }
        }
    }

    public function getPublicData(): array
    {
        $reflect = new ReflectionClass($this);

        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $data = [];
        foreach ($properties as $property) {
            $data[$property->name] = $this->{$property->name};
        }

        return $data;
    }

    public function getAllData(): array
    {
        $reflect = new ReflectionClass($this);

        $properties = $reflect->getProperties();

        $data = [];
        foreach ($properties as $property) {
            $data[$property->name] = $this->{$property->name};
        }

        return $data;
    }
}