<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'exists:election_categories,id',
                Rule::unique('applications')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id)
                                ->where('category_id', $this->input('category_id'));
                }),
            ],
            'statement' => 'required|string|min:20|max:500',
            'manifesto_url' => 'nullable|url|max:500',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'category_id.unique' => 'You have already applied for this category.',
            'category_id.exists' => 'The selected category does not exist.',
            'statement.min' => 'Your motivation statement must be at least 20 characters.',
            'statement.max' => 'Your motivation statement cannot exceed 500 characters.',
            'manifesto_url.url' => 'The manifesto URL must be a valid URL.',
        ];
    }
}
