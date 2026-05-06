<?php

namespace App\Modules\Product\Http\Requests;

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
}
