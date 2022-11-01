<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAndUpdateProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->product;

        return [
            'name'               => ['required', 'min:3', 'max:100', Rule::unique('products')->ignore($id, 'id')],
            'image'              => ['nullable', 'email', Rule::unique('products')->ignore($id, 'id')],
            'price'              => 'required|integer',
            'description'        => 'required|min:3|max:100',
            'quantity_inventory' => 'required|integer',
        ];
    }
}
