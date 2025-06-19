<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class BookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'title' => 'required|string',
            'author' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:available,borrowed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10248'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        if ($this->is('api/*')) {
            $response = ApiResponse::sendResponse(422, 'Please enter all entries correctly.', $validator->errors()->all());
            throw new ValidationException($validator, $response);
        }
    }
}
