<?php

namespace App\Http\Controllers;

use App\Enumerations\OrganizationCategoryEnums;
use App\Enumerations\OrganizationTypeEnums;
use App\Repositories\OrganizationRepository;
use App\Rules\EnumExistsRule;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'telephone' => 'required|regex:/^\d{3,20}$/',
            'address' => 'required|string|max:500',
            'city' => 'required|int|exists:cities,id',
            'website' => 'string|max:100|url',
            'establishedAt' => 'required|date',
            'managerName' => 'required|string|between:5,50',
            'secretaryName' => 'required|string|between:5,50',
            'directors' => 'array',
            'directors.*' => 'string',
            'type' => [
                'required',
                'string',
                new EnumExistsRule(OrganizationTypeEnums::PARENT_ID)
            ],
            'category' => [
                'required',
                'string',
                new EnumExistsRule(OrganizationCategoryEnums::PARENT_ID)
            ],
        ]);

        $repo = new OrganizationRepository();

        if ($repo->userAlreadyHasOrganization(['userId' => auth()->id()])) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                __('درخواست شما مبنی بر ثبت تشکل قبلا ثبت شده است!')
            );
        }

        return $repo->create($request->all())->toArray();
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
}
