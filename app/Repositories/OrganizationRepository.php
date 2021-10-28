<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Enumerations\OrganizationStatusEnums;
use Infrastructure\Traits\prepareOrganizationDataTrait;

class OrganizationRepository
{
    use prepareOrganizationDataTrait;

    public function create(array $data): array
    {
        $organization = new Organization();

        $organization->title = $data['title'];
        $organization->description = $data['description'];
        $organization->type = $data['type'];
        $organization->category = $data['category'];
        $organization->telephone = $data['telephone'];
        $organization->address = $data['address'];
        $organization->website = $data['website'] ?? null;
        $organization->established_at = $data['establishedAt'];
        $organization->manager_name = $data['managerName'];
        $organization->secretary_name = $data['secretaryName'];
        $organization->directors = is_null($data['directors']) ? null : json_encode($data['directors'], JSON_UNESCAPED_UNICODE);

        $organization->status = OrganizationStatusEnums::WAITING_FOR_COMPLETION;
        $organization->created_by = $data['createdBy'];
        $organization->owner_user_id = $data['createdBy'];

        $organization->logo_file_id = $data['logoFileId'];

        $organization->saveOrFail();

        $organization->loadMissing([
            'typeRelation',
            'categoryRelation',
            'statusRelation',
            'logoFileRelation'
        ]);

        $result = $organization->toArray();
        $this->prepareOrganization($result);
        return $result;
    }

    public function userAlreadyHasOrganization(array $data): array
    {
        return ['exists' => Organization::query()->where('owner_user_id', $data['userId'])->exists()];
    }

    public function setStatusAsWaitingForVerification(array $data): array
    {
        $rowCount = Organization::query()
            ->where('id', $data['organizationId'])
            ->update([
                'status' => OrganizationStatusEnums::WAITING_FOR_VERIFICATION
            ]);

        return ['result' => $rowCount == 1];
    }

    public function update(array $data): array
    {
        /** @var Organization $organization */
        $organization = $data['model'];

        $organization->title = $data['title'];
        $organization->description = $data['description'];
        $organization->type = $data['type'];
        $organization->category = $data['category'];
        $organization->telephone = $data['telephone'];
        $organization->address = $data['address'];
        $organization->website = $data['website'] ?? null;
        $organization->established_at = $data['establishedAt'];
        $organization->manager_name = $data['managerName'];
        $organization->secretary_name = $data['secretaryName'];
        $organization->directors = is_null($data['directors']) ? null : json_encode($data['directors'], JSON_UNESCAPED_UNICODE);

        if (isset($data['logoFileId'])) {
            $organization->logo_file_id = $data['logoFileId'];
        }

        $organization->saveOrFail();

        $organization->loadMissing([
            'typeRelation',
            'categoryRelation',
            'statusRelation',
            'logoFileRelation'
        ]);

        $result = $organization->toArray();
        $this->prepareOrganization($result);
        return $result;
    }
}
