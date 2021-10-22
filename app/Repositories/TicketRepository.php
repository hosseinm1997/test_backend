<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Models\User;
use Infrastructure\Interfaces\ThreadRepositoryInterface;
use Infrastructure\Interfaces\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    public function index()
    {
        return Ticket::query()->with('organization' , 'assignedTo', 'createdBy')
            ->filtered()->sorted()->latest()->paginate();
    }

    public function show($ticketId)
    {
        return Ticket::query()->where('id', $ticketId)->firstOrFail();
    }

    public function store(array $data, User $user = null)
    {
        $ticket = Ticket::query()->create([
           'title'              => $data['title'],
           'mobile'             => $data['mobile'],
           'email'              => $data['email'],
           'organization_id'    => $data['organization_id'],
           'created_by'         => optional($user)->id,
        ]);

        $this->createThreadForTicket($data ,$ticket->id, $user);

        return $ticket;
    }

    private function createThreadForTicket(array $data, int $ticketId, User $user = null)
    {
        /* @var ThreadRepositoryInterface $threadRepository  */
        $threadRepository = app(ThreadRepositoryInterface::class);
        $threadRepository->store($ticketId, $data, $user);
    }
}
