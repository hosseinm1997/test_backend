<?php


namespace App\Repositories;


use App\Models\Enumeration;
use Illuminate\Support\Facades\DB;
use App\Enumerations\DocumentTypeEnums;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class EnumerationRepository
{
    public function getAllMissingRequiredDocumentTypes(array $data): array
    {
        $repo = new DocumentRepository();
        /** @var Builder $sub */
        $sub = $repo->getDocumentsBuilder($data)['builder'];

        $builder = Enumeration::query()
            ->leftJoin(
                DB::raw('(' .$sub->toSql() . ') as documents'),
                'enumerations.id',
                'documents.type'
            )
            ->mergeBindings($sub->getQuery())
            ->where('parent_id', DocumentTypeEnums::PARENT_ID);

        if (isset($data['optional'])) {
            $builder->whereRaw("meta_data->>'optional' = '" . $data['optional'] . "'");
        }

        return $builder->whereNull('documents.id')
            ->get(['enumerations.*'])
            ->toArray();
    }
}
