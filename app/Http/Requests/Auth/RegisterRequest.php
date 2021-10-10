<?php

namespace App\Http\Requests\Auth;

use App\Rules\NationalCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'mobile' => ['required','digits:11', "regex:/^(09\\d{9}|16476422280)$/"],
            'nationalCode' => ['required','digits:10',new NationalCodeRule]
        ];
    }
}
