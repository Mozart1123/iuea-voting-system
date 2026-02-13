<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:election_categories,name',
            'description' => 'required|string|min:10|max:1000',
            'icon' => 'required|string|max:50',
            'application_deadline' => 'required|date_format:Y-m-d H:i|after:now',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'A category with this name already exists.',
            'application_deadline.after' => 'The deadline must be in the future.',
        ];
    }
}
