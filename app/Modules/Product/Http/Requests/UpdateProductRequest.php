<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\Product\DTOs\UpdateProductDTO;
use App\Modules\Product\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'sku'                 => ['sometimes', 'string', 'max:100', "unique:products,sku,{$productId}"],
            'name'                => ['sometimes', 'string', 'max:255'],
            'description'         => ['nullable', 'string'],
            'price'               => ['sometimes', 'numeric', 'min:0'],
            'stock_quantity'      => ['sometimes', 'integer', 'min:0'],
            'low_stock_threshold' => ['sometimes', 'integer', 'min:0'],
            'status'              => ['sometimes', new Enum(ProductStatus::class)],
        ];
    }

    public function toDTO(): UpdateProductDTO
    {
        return UpdateProductDTO::fromArray($this->validated());
    }
}
