<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\Product\DTOs\CreateProductDTO;
use App\Modules\Product\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'sku'                 => ['required', 'string', 'max:100', 'unique:products,sku'],
            'name'                => ['required', 'string', 'max:255'],
            'description'         => ['nullable', 'string'],
            'price'               => ['required', 'numeric', 'min:0'],
            'stock_quantity'      => ['sometimes', 'integer', 'min:0'],
            'low_stock_threshold' => ['sometimes', 'integer', 'min:0'],
            'status'              => ['sometimes', new Enum(ProductStatus::class)],
        ];
    }

    public function toDTO(): CreateProductDTO
    {
        return CreateProductDTO::fromArray($this->validated());
    }
}
