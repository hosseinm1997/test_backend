<?php

namespace App\Http\Controllers;

use App\Enumerations\OrganizationStatusEnums;
use App\Http\Requests\Organization\CreateOrganizationRequest;
use App\Repositories\EnumerationRepository;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $repo = new OrganizationRepository();

        return $repo->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        return $repo->setStatusAsWaitingForVerification(['organizationId' => auth_user_organization()->id]);
    }
}
//
