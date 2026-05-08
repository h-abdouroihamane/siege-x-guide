<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOperatorRequest extends FormRequest
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
            'operationName' => 'required|string|unique:operations,name',
            'year' => 'required|numeric',
            'season' => 'required|numeric|in:1,2,3,4',
            'releaseDate' => 'required|date',
            'name' => 'required|string|unique:operators,name',
            'description' => 'required|string',
            'side' => 'required|string|in:Attack,Defense',
            'squad' => 'required|string|exists:squads,name',
            'queerIdentities' => 'sometimes|array',
            'queerIdentities.*' => 'string|exists:queer_identities,name',
            'roles' => 'sometimes|array',
            'roles.*' => 'string|exists:roles,name',
            'portrait' =>
                'required|image|mimes:png|dimensions:max_width=300,max_height=500',
            'icon' =>
                'required|image|mimes:png|dimensions:max_width=250,max_height=250',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute is required',
            'string' => ':attribute should be a string',
            'numeric' => ':attribute should be a number',
            'side.in' => 'Side should be either "Attack" or "Defense"',
            'portrait.dimensions' =>
                'Portraits should be 300x500px (Width x Height) max',
            'icon.dimensions' => 'Icons should be 250x250px max',
        ];
    }
}
