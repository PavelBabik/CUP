<?php

namespace App\Entities;

use ReflectionClass;
use ReflectionProperty;

class BaseEntity
{
    protected function beforeSave(array &$data)
    {
        if (isset($data['id'])) {
            if ($data['id'] == 0) {
                unset($data['id']);
            }
        }

        delete_timestamps($data);
    }

    protected function afterSave(array &$data)
    {

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

        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $data = [];
        foreach ($properties as $property) {
            $data[$property->name] = $this->{$property->name};
        }

        return $data;
    }

    protected function getAllData(): array
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