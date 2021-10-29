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
    public function store(
        array $data,
        User $user = null,
        $fileId,
        int $ticketId,
        int $sendType
    )
    {
        return Thread::create([
            'ticket_id' => $ticketId,
            'attachment_file_id' => $fileId,
            'description' => $data['description'],
            'send_type' => $sendType,
            'sender_user_id' => optional($user)->id
        ]);
    }
}
