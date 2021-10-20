<?php

namespace App\Repositories;

use App\Models\Thread;
use Infrastructure\Interfaces\ThreadRepositoryInterface;

class ThreadRepository implements ThreadRepositoryInterface
{
    public function store($ticketId, array $data)
    {
        return Thread::create([
            'ticket_id' => $ticketId,
            'description' => $data['description']
        ]);
    }
}
