<?php

namespace App\Http\Requests\Radius;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientsUploadRequest extends FormRequest
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
            'file' => 'required|file|mimes:xls,xlsx',
            'keep_clients' => 'required|in:1,2', // 1 para sim, 2 para não
            'client_bond_id' => 'required|exists:client_bonds,id',
            'observations' => 'nullable'
        ];
    }
}
