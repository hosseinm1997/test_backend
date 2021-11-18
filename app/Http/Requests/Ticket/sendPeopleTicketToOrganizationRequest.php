<?php

namespace App\Http\Requests\Ticket;

use App\Rules\ValidMobileRule;
use Illuminate\Validation\Rule;
use App\Enumerations\Ticket\PriorityEnums;
use Illuminate\Foundation\Http\FormRequest;

class sendPeopleTicketToOrganizationRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255',
            'description' => 'required|string',
            'mobile' => ['required', 'string', new ValidMobileRule()],
            'email' => 'required|email',
            'priority' => ['nullable', Rule::in(PriorityEnums::getEnumPriority())],
            'organization_id' => ['required', 'exists:organizations,id'],
            'file' => 'nullable|mimes:jpg,bmp,png'
        ];
    }
}
