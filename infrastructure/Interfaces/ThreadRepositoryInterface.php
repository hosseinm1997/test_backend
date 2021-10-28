<?php

namespace Infrastructure\Interfaces;

use App\Models\User;

interface ThreadRepositoryInterface
{
    public function store(
        array $data,
        User $user = null,
        $fileId,
        int $ticketId,
        int $sendType
    );
}
