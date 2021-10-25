<?php

namespace Infrastructure\Interfaces;

use App\Models\User;

interface ThreadRepositoryInterface
{
    public function store(int $ticketId, $fileId, array $data, User $user = null);
}
