<?php

namespace App\Services;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Repositories\FileRepository;
use App\Enumerations\DocumentTypeEnums;
use App\Enumerations\FileCategoryEnums;
use App\Repositories\DocumentRepository;
use App\Enumerations\DocumentStatusEnums;
use App\Enumerations\OrganizationStatusEnums;

class DocumentUploadService
{
    private $dirMapping;
    private $mandatoryDocument;

    public function __construct()
    {
        $documentClass = new DocumentTypeEnums();

        $this->dirMapping = $documentClass->getDirMapping();
        $this->mandatoryDocument = $documentClass->getMandatoryDocument();
    }

    private function upload(UploadedFile $file, string $directory, int $entityId)
    {
        $directory = $directory . DIRECTORY_SEPARATOR . Hashids::encode($entityId);
        return $file->store($directory);
    }

    private function canStoreThisOrganizationDocument(int $type, Organization $organization): bool
    {
        if (!in_array($type, $this->mandatoryDocument)) {
            return true;
        }

        if ($organization->status != OrganizationStatusEnums::WAITING_FOR_COMPLETION) {
            return false;
        }

        return !$this->hasAnyPendingOrganizationDocument($type, $organization->id);
    }

    private function hasAnyPendingOrganizationDocument(int $type, int $organizationId): bool
    {
        $repo = new DocumentRepository();
        return $repo->anyDocumentOfThisTypeExists([
            'type' => $type,
            'organizationId' => $organizationId,
            'statuses' => [
                DocumentStatusEnums::ACCEPTED_BY_AGENT,
                DocumentStatusEnums::ACCEPTED_BY_MANAGER,
                DocumentStatusEnums::VERIFYING_BY_AGENT,
                DocumentStatusEnums::VERIFYING_BY_MANAGER,
            ]
        ])['result'];
    }

    private function hasAnyPendingUserDocument(int $type, int $userId): bool
    {
        $repo = new DocumentRepository();
        return $repo->anyDocumentOfThisTypeExists([
            'type' => $type,
            'userId' => $userId,
            'statuses' => [
                DocumentStatusEnums::ACCEPTED_BY_AGENT,
                DocumentStatusEnums::ACCEPTED_BY_MANAGER,
                DocumentStatusEnums::VERIFYING_BY_AGENT,
                DocumentStatusEnums::VERIFYING_BY_MANAGER,
            ]
        ])['result'];
    }

    public function storeOrganizationDocument(int $type, UploadedFile $file, Organization $organization): array
    {
        if (!$this->canStoreThisOrganizationDocument($type, $organization)) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
        'مدارک شما ارسال شده است و امکان ارسال مجدد نمی باشد!'
            );
        }

        try {
            $address = $this->upload($file, $this->dirMapping[$type], $organization->id);
        } catch (\Throwable $exception) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'امکان ارسال فایل وجود ندارد!'
            );
        }

        try {
            DB::beginTransaction();

            $repo = new FileRepository();
            $fileEntity = $repo->create([
                'address' => $address,
                'contentType' => $file->getType(),
                'category' => FileCategoryEnums::DOCUMENT,
            ]);

            $documentRepo = new DocumentRepository();

            $documentRepo->deleteDocumentsOfType([
                'type' => $type,
                'organizationId' => $organization->id,
                'statuses' => [
                    DocumentStatusEnums::WAITING_FOR_VERIFICATION,
                    DocumentStatusEnums::REJECTED_BY_AGENT,
                    DocumentStatusEnums::REJECTED_BY_MANAGER,
                ],
            ]);

            $documentEntity = $documentRepo->create([
                'file_id' => $fileEntity['id'],
                'type' => $type,
                'status' => in_array($type, $this->mandatoryDocument)
                    ? DocumentStatusEnums::WAITING_FOR_VERIFICATION
                    : DocumentStatusEnums::NO_NEED_TO_VERIFY,
                'organization_id' => $organization->id
            ]);

            DB::commit();

            return [
                'url' => route('document.show', ['id' => $documentEntity['id']]),
                'document_id' => $documentEntity['id']
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }

    }

    public function storeUserDocument(int $type, UploadedFile $file, User $user): array
    {
        if ($type != DocumentTypeEnums::NATIONAL_CARD_PICTURE) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'نوع مدرک معتبر نیست!'
            );
        }

        if ($this->hasAnyPendingUserDocument($type, $user->id)) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'مدارک شما ارسال شده است و امکان ارسال مجدد نمی باشد!'
            );
        }

        try {
            $address = $this->upload($file, $this->dirMapping[$type], $user->id);
        } catch (\Throwable $exception) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'امکان ارسال فایل وجود ندارد!'
            );
        }

        try {
            DB::beginTransaction();

            $repo = new FileRepository();
            $fileEntity = $repo->create([
                'address' => $address,
                'contentType' => $file->getType(),
                'category' => FileCategoryEnums::DOCUMENT,
            ]);

            $documentRepo = new DocumentRepository();

            $documentRepo->deleteDocumentsOfType([
                'type' => $type,
                'userId' => $user->id,
                'statuses' => [
                    DocumentStatusEnums::WAITING_FOR_VERIFICATION,
                    DocumentStatusEnums::REJECTED_BY_AGENT,
                    DocumentStatusEnums::REJECTED_BY_MANAGER,
                ],
            ]);

            $documentEntity = $documentRepo->create([
                'file_id' => $fileEntity['id'],
                'type' => $type,
                'status' => in_array($type, $this->mandatoryDocument)
                    ? DocumentStatusEnums::WAITING_FOR_VERIFICATION
                    : DocumentStatusEnums::NO_NEED_TO_VERIFY,
                'user_id' => $user->id
            ]);

            DB::commit();

            return [
                'url' => route('document.show', ['id' => $documentEntity['id']]),
                'document_id' => $documentEntity['id']
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }

    }
}
