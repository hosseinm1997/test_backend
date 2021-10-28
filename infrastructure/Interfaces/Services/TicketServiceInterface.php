<?php

namespace Infrastructure\Interfaces\Services;

use App\Models\User;

interface TicketServiceInterface
{
    public function createTicket(array $data, User $user = null, int $sendType, int $receiptType);
}
