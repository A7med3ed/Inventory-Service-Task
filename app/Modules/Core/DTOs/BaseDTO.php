<?php

namespace App\Modules\Core\DTOs;

abstract class BaseDTO
{
    abstract public function toArray(): array;

    abstract public static function fromArray(array $data): self;

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        return fromArray($data);
    }
}
