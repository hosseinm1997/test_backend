<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Enumerations\OrganizationStatusEnums;

class OrganizationRepository
{
    public function create(array $data)
    {
        $organization = new Organization();

        $organization->title = $data['title'];
        $organization->description = $data['description'];
        $organization->type = $data['type'];
        $organization->category = $data['category'];
        $organization->telephone = $data['telephone'];
        $organization->address = $data['address'];
        $organization->city = $data['city'];
        $organization->website = $data['website'] ?? null;
        $organization->established_at = $data['establishedAt'];
        $organization->manager_name = $data['managerName'];
        $organization->secretary_name = $data['secretaryName'];
        $organization->directors = is_null($data['directors']) ? null : json_encode($data['directors'], JSON_UNESCAPED_UNICODE);

        $organization->status = OrganizationStatusEnums::WAITING_FOR_COMPLETION;
        $organization->created_by = auth()->id();
        $organization->owner_user_id = auth()->id();

        $organization->saveOrFail();

        $organization->loadMissing([
            'typeRelation',
            'categoryRelation',
            'statusRelation',
            'cityRelation.provinceRelation',
        ]);

        return $organization;
    }

    public function userAlreadyHasOrganization(array $data)
    {
        return Organization::query()->where('owner_user_id', $data['userId'])->exists();
    }
}
