<?php

namespace Infrastructure\Interfaces;

use App\Models\User;

interface TicketRepositoryInterface
{
    public function index();

    public function getTicketsForOrganization();

    public function show(int $ticketId);

    public function createPeopleTicket(array $data, User $user = null);
}
