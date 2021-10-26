<?php

namespace App\Repositories;

use App\Enumerations\FileCategoryEnums;
use App\Models\File;

class FileRepository
{
    public function create(array $data)
    {
        $file = new File();
        $file->address = $data['address'];
        $file->content_type = $data['contentType'];
        $file->category = $data['category'];
        $file->saveOrFail();

        return $file->toArray();
    }

    public function getFileById(array $data): array
    {
        return File::query()
            ->where('id', $data['fileId'])
            ->where('category', '!=', FileCategoryEnums::DOCUMENT)
            ->firstOrFail()
            ->toArray()
        ;
    }
}
