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
			  'min_period' => 'numeric|gte:0',
			  'min_amount' => 'numeric|gt:0',
              'max_period' => 'required_with:min_period|numeric|gte:0|lte:36',
			  'max_amount' => 'required_with:min_amount|numeric|gt:0',
        ];
    }
	public function messages()
	{
		return [
			'max_period.required' => 'A max period is required',
			'max_amount.required'  => 'A max amount is required',
		];
	}
}

