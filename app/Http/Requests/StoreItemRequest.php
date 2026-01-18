<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:items,code',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:good,damaged,maintenance',
            'image' => 'nullable|image|max:1024', // 1MB Max
        ];
    }
}
