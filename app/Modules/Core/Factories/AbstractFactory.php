<?php

namespace App\Modules\Core\Factories;

abstract class AbstractFactory
{
    abstract public function create(array $data = []);
    
    public function createMultiple(int $count, array $data = []): array
    {
        $items = [];
        
        for ($i = 0; $i < $count; $i++) {
            $items[] = $this->create($data);
        }
        
        return $items;
    }
    
    protected function mergeDefaults(array $data, array $defaults): array
    {
        return array_merge($defaults, $data);
    }
    
    protected function filterNullValues(array $data): array
    {
        return array_filter($data, fn($value) => $value !== null);
    }
}
