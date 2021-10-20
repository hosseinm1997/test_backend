<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Validation\Rule;
use App\Enumerations\PriorityEnums;
use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'required|email',
            'priority' => ['nullable', Rule::in(PriorityEnums::getEnumPriority())]
        ];
    }
}
