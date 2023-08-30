<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToursListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
                'filter.price_from' => 'numeric',
                'filter.price_to' => 'numeric',
                'sort' => Rule::in(['price']),
            ];
    }

    public function messages(): array
    {
        return [
            'sort' => "The 'sort' parameter accepts only 'price' value",
            'filter.price_from' => "The 'price_form' parameter must be numeric",
            'filter.price_to' => "The 'price_to' parameter must be numeric",
        ];
    }


}
