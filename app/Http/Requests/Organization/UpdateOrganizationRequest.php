<?php

namespace App\Http\Requests\Organization;

use App\Enumerations\OrganizationStatusEnums;
use App\Rules\EnumExistsRule;
use App\Enumerations\OrganizationTypeEnums;
use Illuminate\Foundation\Http\FormRequest;
use App\Enumerations\OrganizationCategoryEnums;
use Symfony\Component\HttpFoundation\Response;

class UpdateOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (
            !in_array(auth_user_organization()->status, [
                OrganizationStatusEnums::WAITING_FOR_COMPLETION,
                OrganizationStatusEnums::REJECTED_BY_AGENT,
                OrganizationStatusEnums::REJECTED_BY_MANAGER,
            ])
        ) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                __('برای تغییر مشخصات، انجمن باید در وضعیت در حال تکمیل یا رد شده باشد!')
            );
        }

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
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'telephone' => 'required|regex:/^\d{3,20}$/',
            'address' => 'required|string|max:500',
            'website' => 'required|string|max:100|url',
            'establishedAt' => 'required|date',
            'managerName' => 'required|string|between:5,50',
            'secretaryName' => 'required|string|between:5,50',
            'directors' => 'required|array',
            'directors.*' => 'string',
            'logo' => 'file',
            'type' => [
                'required',
                'int',
                new EnumExistsRule(OrganizationTypeEnums::PARENT_ID)
            ],
            'category' => [
                'required',
                'int',
                new EnumExistsRule(OrganizationCategoryEnums::PARENT_ID)
            ],
        ];
    }
}
