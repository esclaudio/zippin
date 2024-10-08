<?php

namespace App\Http\Requests\Api;

use App\Enums\CurrencyCode;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', Rule::exists(User::class)],
            'currency_code' => ['required', Rule::enum(CurrencyCode::class)],
            'subtotal' => ['required', 'numeric', 'gt:0'],
            'discount' => ['required', 'numeric', 'gte:0'],
            'total' => ['sometimes', 'numeric', 'gte:0'],
            'billing.name' => ['required'],
            'billing.phone' => ['required'],
            'billing.email' => ['required', 'email'],
            'billing.address1' => ['required'],
            'billing.address2' => ['required'],
            'billing.zipcode' => ['required'],
            'billing.city' => ['required'],
            'billing.province' => ['required'],
            'billing.country' => ['required'],
            'shipping.name' => ['required'],
            'shipping.phone' => ['required'],
            'shipping.email' => ['required', 'email'],
            'shipping.address1' => ['required'],
            'shipping.address2' => ['required'],
            'shipping.zipcode' => ['required'],
            'shipping.city' => ['required'],
            'shipping.province' => ['required'],
            'shipping.country' => ['required'],
            'lines.*.product_id' => ['required', Rule::exists(Product::class)],
            'lines.*.quantity' => ['required', 'numeric', 'gte:1'],
        ];
    }
}
