<?php

namespace App\Classes;

class Templates
{
    private $data = [];

    public function setData(array $payload): void
    {
        $this->data = $payload;
    }

    public function setMeta(array $payload): void
    {
        $this->data['meta'] = $payload;
    }

    public function render(string $componentName): string
    {
        return view($componentName, $this->data);
    }
}