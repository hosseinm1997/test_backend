<?php

namespace App\Repositories;

use App\Enumerations\FileCategoryEnums;
use App\Models\User;
use App\Models\Thread;
use App\Enumerations\DocumentTypeEnums;
use Infrastructure\Interfaces\ThreadRepositoryInterface;
use Vinkla\Hashids\Facades\Hashids;

class ThreadRepository implements ThreadRepositoryInterface
{
    /**
     * @throws \Exception
     */
    public function store($ticketId, array $data, User $user = null)
    {
//        if (isset($data['organization_id'])) {
//            $entityId = $data['organization_id'];
//        } elseif(isset($data['assigned_to'])) {
//            $entityId = $data['assigned_to'];
//        }

        $dir = 'tickets/' . Hashids::encode($ticketId);

        $fileUploaded = uploadFile($data['file'], $dir, FileCategoryEnums::THREAD_ATTACHMENT);

        return Thread::create([
            'ticket_id' => $ticketId,
            'description' => $data['description'],
            'attachment_file_id' => $fileUploaded['id']
        ]);
    }
}
