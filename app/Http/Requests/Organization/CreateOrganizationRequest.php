<?php

namespace App\Http\Requests\Organization;

use App\Enumerations\OrganizationCategoryEnums;
use App\Enumerations\OrganizationTypeEnums;
use App\Repositories\OrganizationRepository;
use App\Rules\EnumExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class CreateOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $repo = new OrganizationRepository();

        if ($repo->userAlreadyHasOrganization(['userId' => auth()->id()])) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                __('درخواست شما مبنی بر ثبت تشکل قبلا ثبت شده است!')
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
            'city' => 'required|integer|exists:cities,id',
            'website' => 'string|max:100|url',
            'establishedAt' => 'required|date',
            'managerName' => 'required|string|between:5,50',
            'secretaryName' => 'required|string|between:5,50',
            'directors' => 'array',
            'directors.*' => 'string',
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'telephone.regex' => 'شماره تلفن صحیح نمی باشد!',
        ];
    }
}
