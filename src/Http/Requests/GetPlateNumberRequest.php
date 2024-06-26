<?php

namespace MPWT\VRDL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetPlateNumberRequest extends FormRequest
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
			'is_request'	=> 'nullable|in:0,1',
			'is_sale'		=> 'nullable|in:0,1',
			'plate_number'	=> 'nullable|max:8|regex:/^[a-zA-Z0-9.-]+$/'
		];
    }
}