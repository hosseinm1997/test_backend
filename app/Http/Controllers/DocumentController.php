<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDocumentRequest;
use App\Repositories\DocumentRepository;
use App\Services\DocumentUploadService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $repo = new DocumentRepository();
        return $repo->getAllDocuments([
            'organizationId' => auth_user_organization()->id,
            'userId' => auth_user()->id
        ]);
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
     * @return array
     * @throws \Throwable
     */
    public function storeForOrganization(CreateDocumentRequest $request)
    {
        $organization = auth_user_organization();
        $service = new DocumentUploadService();

        return $service->storeOrganizationDocument(
            $request->input('type'),
            $request->file('file'),
            $organization
        );
    }

    public function storeForUser(CreateDocumentRequest $request)
    {
        $service = new DocumentUploadService();

        return $service->storeUserDocument(
            $request->input('type'),
            $request->file('file'),
            auth_user()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return BinaryFileResponse
     */
    public function show($id)
    {
        $repo = new DocumentRepository();
        $document = $repo->getDocumentById(compact('id'));
        // todo: check policy
        //auth_user()->roleRelation != null
        //auth_user()->id == documents->user_id
        //auth_user()->id == document->organizationRelation->owner_user_id
        return response()->file(storage_app_path($document['file_relation']['address']));
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
