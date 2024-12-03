<?php

namespace App\Http\Requests\Radius;

use App\Models\ClientBond;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreClientBondRequest extends FormRequest
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
            'server' => 'required|in:'.ClientBond::SERVIDORES.','.ClientBond::DISCENTES,
            'description' => 'required|min:3',
            'priority' => 'required|numeric',
            'value' => 'required|numeric',
        ];
    }
}
