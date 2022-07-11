<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        return [
            'name' => 'sometimes|required|unique:products,name,'.$this->product->id,
            'price' => 'sometimes|required|numeric',
            'description' => 'sometimes|required|string',
            'category' => 'sometimes|required|string',
            'image' => 'url',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Title',
            'price' => 'Price',
            'description' => 'Description',
            'category' => 'Category',
            'image' => 'Image'
        ];
    }
}
