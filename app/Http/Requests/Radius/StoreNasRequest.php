<?php

namespace App\Http\Requests\Radius;

use Illuminate\Foundation\Http\FormRequest;

class StoreNasRequest extends FormRequest
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
            'nasname' => 'required|min:3',
            'shortname' => 'required|string',
            'ports' => 'required|int',
            'secret' => 'required|string',
            'server' => 'nullable|string',
            'community' => 'nullable|string',
            'description' => 'required|string',
        ];
    }
}
