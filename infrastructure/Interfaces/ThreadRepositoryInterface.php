<?php

namespace Infrastructure\Interfaces;

use App\Models\User;

interface ThreadRepositoryInterface
{
    public function store($ticketId, array $data, User $user = null);
}
