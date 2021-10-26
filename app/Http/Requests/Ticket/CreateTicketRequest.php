<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Validation\Rule;
use App\Enumerations\Ticket\TypeEnum;
use App\Enumerations\Ticket\PriorityEnums;
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
        $enumType = TypeEnum::getAllEnumType();

        return [
            'title' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'required|email',
            'priority' => ['nullable', Rule::in(PriorityEnums::getEnumPriority())],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'file' => 'nullable|mimes:jpg,bmp,png',
            'send_type' => ['required', 'int', Rule::in($enumType)],
            'receipt_type' => ['required', 'int', Rule::in($enumType)],
        ];
    }
}
