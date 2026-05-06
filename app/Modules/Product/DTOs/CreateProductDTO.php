<?php

namespace App\Modules\Product\DTOs;

readonly class CreateProductDTO
{
    public function __construct(
        public string  $name,
        public string  $slug,
        public string  $sku,
        public string  $description,
        public float   $price,
        public int     $stock_quantity,
        public int     $low_stock_threshold = 10,
        public string  $status = 'active',
        public ?int    $category_id = null,
        public ?string $image_url = null,
        public bool    $is_active = true
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'low_stock_threshold' => $this->low_stock_threshold,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'image_url' => $this->image_url,
            'is_active' => $this->is_active,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'],
            sku: $data['sku'],
            description: $data['description'],
            price: (float) $data['price'],
            stock_quantity: (int) $data['stock_quantity'],
            low_stock_threshold: isset($data['low_stock_threshold']) ? (int) $data['low_stock_threshold'] : 10,
            status: $data['status'] ?? 'active',
            category_id: isset($data['category_id']) ? (int) $data['category_id'] : null,
            image_url: $data['image_url'] ?? null,
            is_active: $data['is_active'] ?? true
        );
    }
}
