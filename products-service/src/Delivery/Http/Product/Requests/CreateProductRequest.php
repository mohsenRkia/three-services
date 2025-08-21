<?php

namespace src\Delivery\Http\Product\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'price' => 'required|numeric|min:0'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
