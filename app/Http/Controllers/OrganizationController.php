<?php

namespace App\Http\Controllers;

use App\Enumerations\FileCategoryEnums;
use App\Enumerations\OrganizationStatusEnums;
use App\Http\Requests\Organization\CreateOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Models\Organization;
use App\Repositories\EnumerationRepository;
use App\Repositories\OrganizationRepository;
use Hekmatinasser\Verta\Verta;
use Infrastructure\Traits\prepareOrganizationDataTrait;
use Symfony\Component\HttpFoundation\Response;

class OrganizationController extends Controller
{
    use prepareOrganizationDataTrait;

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $organizations = Organization::where('status',OrganizationStatusEnums::ACCEPTED_BY_MANAGER)->get()->toArray();

        foreach ($organizations as &$organization) {
            $this->prepareOrganization($organization);
        }

        return $organizations;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateOrganizationRequest $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CreateOrganizationRequest $request)
    {
        $data = $request->all();
        $data['createdBy'] = auth()->id();
        $data['logoFileId'] = uploadFile(
            $request->file('logo'),
            'public/organizations/logos',
            FileCategoryEnums::ORGANIZATION_ATTRIBUTES
        )['id'];

        $repo = new OrganizationRepository();
        return $repo->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @return array
     */
    public function show()
    {
        $data = auth_user_organization()->toArray();
        $this->prepareOrganization($data);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrganizationRequest $request
     * @return array
     * @throws \Exception
     */
    public function update(UpdateOrganizationRequest $request)
    {
        $repo = new OrganizationRepository();
        $data = $request->all();
        $data['model'] = auth_user_organization();

        if ($request->hasFile('logo')) {
            $data['logoFileId'] = uploadFile(
                $request->file('logo'),
                'public/organizations/logos',
                FileCategoryEnums::ORGANIZATION_ATTRIBUTES
            )['id'];
        }

        return $repo->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function documentsCompleted()
    {
        if (auth_user_organization()->status != OrganizationStatusEnums::WAITING_FOR_COMPLETION) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'وضعیت تشکل باید در حال تکمیل مدارک باشد!'
            );
        }

        $repo = new EnumerationRepository();
        $missingDocTypes = $repo->getAllMissingRequiredDocumentTypes([
            'organizationId' => auth_user_organization()->id,
            'userId' => auth_user()->id,
            'optional' => 'false',
        ]);

        if (count($missingDocTypes) > 0) {
            $missingDocTitles = collect($missingDocTypes)->pluck('title')->implode(',');

            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'مدارک زیر ارسال نشده اند:' . ',' . $missingDocTitles
            );
        }

        $repo = new OrganizationRepository();
        $result = $repo->setStatusAsWaitingForVerification(['organizationId' => auth_user_organization()->id]);

        sendSmsByPattern(
            auth_user()->mobile,
            config('pattern.successful_upload'),
            [
                'time' => (new Verta())->format('Y/m/d H:i:s'),
                'code' => env('PANEL_URL') . '/panel/profile'
            ]
        );

        return $result;
    }
}
