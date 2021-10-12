<?php

namespace App\Http\Requests;

use App\Enumerations\DocumentTypeEnums;
use App\Rules\EnumExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentRequest extends FormRequest
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
            'type' => [
                'required',
                'int',
                new EnumExistsRule(DocumentTypeEnums::PARENT_ID)
            ],
            'file' => 'required|file|max:5120'
        ];
    }
}
