<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditPostInvestRequest extends FormRequest
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
              'investment' => 'required|numeric|gt:0',
        ];
    }
	public function messages()
	{
		return [
			'investment.required' => 'Enter a numeric value, please',
			'investment.numeric'  => 'Enter a numeric value, please',
			'investment.gt'       => 'Enter positive number, please', 
		];
	}
}

