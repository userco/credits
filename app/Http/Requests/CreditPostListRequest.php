<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditPostListRequest extends FormRequest
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
              'maxp' => 'required',
			  'maxa' => 'required',
        ];
    }
	public function messages()
	{
		return [
			'maxp.required' => 'A max period is required',
			'maxa.required'  => 'A max amount is required',
		];
	}
}

