<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'details' => ['required', 'array'],
            'details.fullName' => ['required', 'string','min:3'],
            'details.phone' => ['required', 'regex:/^0(5|6|7)[0-9]{8}$/'],
            'details.email' => ['sometimes','nullable','string','email'],
            'details.province' => ['required','array'],
            'details.town' => ['sometimes','nullable'],
            'details.address' => ['required','string'],

            'items' => ['required', 'array','min:1'],
        ];
    }
}
