<?php

namespace App\Repositories;

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
}
