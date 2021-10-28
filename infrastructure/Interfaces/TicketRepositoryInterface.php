<?php

namespace Infrastructure\Interfaces;

use App\Models\User;

interface TicketRepositoryInterface
{
    public function index();

    public function getTicketsForOrganization();

    public function show(int $ticketId);

    public function store(
        array $data,
        User $user = null,
        int $sendType,
        int $receiptType
    );
}
