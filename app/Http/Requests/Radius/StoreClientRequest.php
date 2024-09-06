<?php

namespace App\Http\Requests\Radius;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreClientRequest extends FormRequest
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
            'name' => 'required|min:3',
            'registry' => 'required|numeric',
            'cpf' => 'required|numeric|digits:11',
            'birth' => 'required|date',
            'email' => 'nullable|email',
            'client_bond_id' => 'required|exists:client_bonds,id',
            'observations' => 'nullable',
            'pass' => ['required', Password::min(6)],
        ];
    }
}
