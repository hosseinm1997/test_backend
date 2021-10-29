<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Validation\Rule;
use App\Enumerations\Ticket\PriorityEnums;
use Illuminate\Foundation\Http\FormRequest;

class CreateTicketToManagementRequest extends FormRequest
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
            'title' => 'required|string|min:2|max:255',
            'description' => 'required|string',
            'file' => 'nullable|mimes:jpg,bmp,png'
        ];
    }
}
