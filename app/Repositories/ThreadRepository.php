<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Thread;
use App\Enumerations\DocumentTypeEnums;
use Infrastructure\Interfaces\ThreadRepositoryInterface;

class ThreadRepository implements ThreadRepositoryInterface
{
    /**
     * @throws \Exception
     */
    public function store($ticketId, array $data, User $user)
    {
        $fileUploaded = uploadFile($data['file'],DocumentTypeEnums::THREAD, $user->id);

        return Thread::create([
            'ticket_id' => $ticketId,
            'description' => $data['description'],
            'attachment_file_id' => $fileUploaded['id']
        ]);
    }
}
