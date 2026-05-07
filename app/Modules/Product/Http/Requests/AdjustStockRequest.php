<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\Product\DTOs\AdjustStockDTO;
use Illuminate\Foundation\Http\FormRequest;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'action'   => ['required', 'in:increment,decrement'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function toDTO(): AdjustStockDTO
    {
        return AdjustStockDTO::fromArray($this->validated());
    }
}
