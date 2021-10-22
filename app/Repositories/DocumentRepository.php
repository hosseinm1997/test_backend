<?php

namespace App\Repositories;

use App\Enumerations\DocumentStatusEnums;
use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;

class DocumentRepository
{
    public function create(array $data): array
    {
        return Document::query()->create($data)->toArray();
    }

    public function anyDocumentOfThisTypeExists(array $data): array
    {
        $builder = Document::query();

        if (array_key_exists('organizationId', $data)) {
            $builder->where('organization_id', $data['organizationId']);
        } else {
            $builder->where('user_id', $data['userId']);
        }

        return [
            'result' => $builder
                ->where('type', $data['type'])
                ->whereIn('status', $data['statuses'])
                ->exists()
        ];
    }

    public function deleteDocumentsOfType(array $data): array
    {
        $builder = Document::query();

        if (array_key_exists('organizationId', $data)) {
            $builder->where('organization_id', $data['organizationId']);
        } else {
            $builder->where('user_id', $data['userId']);
        }

        $result = $builder
            ->where('type', $data['type'])
            ->whereIn('status', $data['statuses'])
            ->delete();

        return [
            'result' => $result
        ];
    }

    public function getDocumentById(array $data): array
    {
        return Document::with('fileRelation')->findOrFail($data['id'])->toArray();
    }

    public function getDocumentsBuilder(array $data): array
    {
        $builder = Document::query()->where(function (Builder $builder) use ($data) {
            $builder->where('organization_id', $data['organizationId']);
            $builder->orWhere('user_id', $data['userId']);
        })->where('status', DocumentStatusEnums::WAITING_FOR_VERIFICATION);

        return [
            'builder' => $builder
        ];
    }
}
