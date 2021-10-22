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
    public function store($ticketId, array $data, User $user = null)
    {
        if (isset($data['organization_id'])) {
            $entityId = $data['organization_id'];
        } elseif(isset($data['assigned_to'])) {
            $entityId = $data['assigned_to'];
        }

        $fileUploaded = uploadFile($data['file'],DocumentTypeEnums::THREAD, $entityId);

        return Thread::create([
            'ticket_id' => $ticketId,
            'description' => $data['description'],
            'attachment_file_id' => $fileUploaded['id']
        ]);
    }
}
