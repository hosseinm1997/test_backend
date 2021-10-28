<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Ticket;
use App\Enumerations\Ticket\TypeEnum;
use Infrastructure\Interfaces\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    public function index()
    {
        return Ticket::query()->with('organization' , 'assignedTo', 'createdBy')
            ->filtered()->sorted()->latest()->paginate();
    }

    public function getTicketsForOrganization()
    {
        $organization = auth_user_organization();

        return Ticket::query()
            ->where('organization_id', $organization->id)
            ->filtered()->sorted()->latest()->paginate();
    }

    public function show($ticketId)
    {
        return Ticket::query()->where('id', $ticketId)->firstOrFail();
    }

    public function createPeopleTicket(array $data, User $user = null)
    {
        return Ticket::query()->create([
           'title'              => $data['title'],
           'name'               => $data['name'],
           'mobile'             => $data['mobile'],
           'email'              => $data['email'],
           'organization_id'    => $data['organization_id'],
           'created_by'         => optional($user)->id,
           'send_type'          => TypeEnum::PEOPLE,
           'receipt_type'       => TypeEnum::ORGANIZATION,
        ]);
    }
}
