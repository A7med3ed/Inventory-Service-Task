<?php

namespace App\Modules\Product\DTOs;

class UpdateProductDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $slug = null,
        public readonly ?string $description = null,
        public readonly ?float $price = null,
        public readonly ?int $stock_quantity = null,
        public readonly ?int $category_id = null,
        public readonly ?string $image_url = null,
        public readonly ?bool $is_active = null
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'category_id' => $this->category_id,
            'image_url' => $this->image_url,
            'is_active' => $this->is_active,
        ], fn($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            price: isset($data['price']) ? (float) $data['price'] : null,
            stock_quantity: isset($data['stock_quantity']) ? (int) $data['stock_quantity'] : null,
            category_id: isset($data['category_id']) ? (int) $data['category_id'] : null,
            image_url: $data['image_url'] ?? null,
            is_active: $data['is_active'] ?? null
        );
    }
}
