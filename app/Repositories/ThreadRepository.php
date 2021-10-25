<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Thread;
use Infrastructure\Interfaces\ThreadRepositoryInterface;

class ThreadRepository implements ThreadRepositoryInterface
{
    /**
     * @throws \Exception
     */
    public function store(int $ticketId, $fileId, array $data, User $user = null)
    {
        return Thread::create([
            'ticket_id' => $ticketId,
            'description' => $data['description'],
            'attachment_file_id' => $fileId
        ]);
    }
}
