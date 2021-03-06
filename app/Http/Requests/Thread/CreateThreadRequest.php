<?php

namespace App\Http\Requests\Thread;

use Illuminate\Validation\Rule;
use App\Enumerations\Ticket\TypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateThreadRequest extends FormRequest
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
            'ticket_id' => 'required|exists:tickets,id',
            'description' => 'required|string',
            'file' => 'nullable|mimes:jpg,bmp,png',
        ];
    }
}
