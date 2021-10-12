<?php

namespace App\Services;

use App\Enumerations\DocumentStatusEnums;
use App\Enumerations\DocumentTypeEnums;
use App\Enumerations\FileCategoryEnums;
use App\Enumerations\OrganizationStatusEnums;
use App\Models\Organization;
use App\Models\User;
use App\Repositories\DocumentRepository;
use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class DocumentUploadService
{
    const DIR_MAPPING = [
        DocumentTypeEnums::NATIONAL_CARD_PICTURE => 'documents/users/identities',
        DocumentTypeEnums::STATUTE_PICTURE => 'documents/organizations/identities',
        DocumentTypeEnums::OFFICIAL_JOURNAL_PICTURE => 'documents/organizations/identities',
        DocumentTypeEnums::SERVICE_INTRODUCTION_FORM => 'documents/organizations/identities',
        DocumentTypeEnums::SECRETARY_INTRODUCTION_LETTER_PICTURE => 'documents/organizations/identities',
        DocumentTypeEnums::REGISTRATION_CERTIFICATE => 'documents/organizations/identities',
        DocumentTypeEnums::ACTIVITY_LICENSE => 'documents/organizations/identities',
        DocumentTypeEnums::LOCATION_INFO_PICTURE => 'documents/organizations/identities',
        DocumentTypeEnums::ORGANIZATION_MEMBERS => 'documents/organizations/introductions',
        DocumentTypeEnums::ORGANIZATION_COMPANIES => 'documents/organizations/introductions',
        DocumentTypeEnums::ORGANIZATION_BOOK => 'documents/organizations/introductions',
        DocumentTypeEnums::ORGANIZATION_MAGAZINE => 'documents/organizations/introductions',
        DocumentTypeEnums::ORGANIZATION_ARTICLE => 'documents/organizations/introductions',
        DocumentTypeEnums::RESEARCH_COURSE => 'documents/organizations/introductions',
        DocumentTypeEnums::MEETING_AND_SEMINAR => 'documents/organizations/introductions',
        DocumentTypeEnums::INVENTION => 'documents/organizations/introductions',
    ];

    const MANDATORY_DOCUMENTS = [
        DocumentTypeEnums::NATIONAL_CARD_PICTURE,
        DocumentTypeEnums::STATUTE_PICTURE,
        DocumentTypeEnums::OFFICIAL_JOURNAL_PICTURE,
        DocumentTypeEnums::SERVICE_INTRODUCTION_FORM,
        DocumentTypeEnums::SECRETARY_INTRODUCTION_LETTER_PICTURE,
        DocumentTypeEnums::REGISTRATION_CERTIFICATE,
        DocumentTypeEnums::ACTIVITY_LICENSE,
        DocumentTypeEnums::LOCATION_INFO_PICTURE,
    ];

    private function upload(UploadedFile $file, string $directory, int $entityId)
    {
        $directory = $directory . DIRECTORY_SEPARATOR . Hashids::encode($entityId);
        return $file->store($directory);
    }

    private function canStoreThisOrganizationDocument(int $type, Organization $organization): bool
    {
        if (!in_array($type, self::MANDATORY_DOCUMENTS)) {
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
            throw new \Exception('مدارک شما ارسال شده است و امکان ارسال مجدد نمی باشد!');
        }

        try {
            $address = $this->upload($file, self::DIR_MAPPING[$type], $organization->id);
        } catch (\Throwable $exception) {
            throw new \Exception('امکان ارسال فایل وجود ندارد!');
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
                'status' => in_array($type, self::MANDATORY_DOCUMENTS)
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
            throw new \Exception('نوع مدرک معتبر نیست!');
        }

        if ($this->hasAnyPendingUserDocument($type, $user->id)) {
            throw new \Exception('مدارک شما ارسال شده است و امکان ارسال مجدد نمی باشد!');
        }

        try {
            $address = $this->upload($file, self::DIR_MAPPING[$type], $user->id);
        } catch (\Throwable $exception) {
            dd($exception);
            throw new \Exception('امکان ارسال فایل وجود ندارد!', );
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
                'status' => in_array($type, self::MANDATORY_DOCUMENTS)
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
