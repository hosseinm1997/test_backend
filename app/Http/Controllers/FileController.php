<?php

namespace App\Http\Controllers;

use App\Repositories\FileRepository;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function show($id)
    {
        // todo: check permission

        $repo = new FileRepository();
        $file = $repo->getFileById(['fileId' => $id]);

        return response()->file(storage_app_path($file['address']));
    }
}
