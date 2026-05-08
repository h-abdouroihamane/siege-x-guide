<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditOperatorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'side' => 'required|string|in:Attack,Defense',
            'squad' => 'required|string|exists:squads,name',
            'operation_id' => 'required|string|exists:operations,id',
            'queerIdentities' => 'sometimes|array',
            'queerIdentities.*' => 'string|exists:queer_identities,name',
            'roles' => 'sometimes|array',
            'roles.*' => 'string|exists:roles,name',
            'portrait' =>
                'sometimes|nullable|image|mimes:png|dimensions:max_width=300,max_height=500',
            'icon' =>
                'sometimes|nullable|image|mimes:png|dimensions:max_width=250,max_height=250',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute is required',
            'string' => ':attribute should be a string',
            'side.in' => 'Side should be either "Attack" or "Defense"',
            'portrait.dimensions' =>
                'Portraits should be 300x500px (Width x Height) max',
            'icon.dimensions' => 'Icons should be 250x250px max',
        ];
    }
}
