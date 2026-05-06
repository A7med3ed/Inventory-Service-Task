<?php

namespace App\Modules\Core\Factories;

abstract class AbstractFactory
{
    abstract public function make(array $data): object;

    protected function mergeDefaults(array $data, array $defaults): array
    {
        return array_merge($defaults, $data);
    }
}
