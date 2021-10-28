<?php

namespace Infrastructure\Interfaces;

use App\Models\User;

interface ThreadRepositoryInterface
{
    public function store(
        int $ticketId,
        int $sendType,
        $fileId,
        array $data,
        User $user = null
    );
}
