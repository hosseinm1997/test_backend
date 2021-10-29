<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Ticket;
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

    public function store(
        array $data,
        User $user = null,
        int $sendType,
        int $receiptType
        )
    {
        return Ticket::query()->create([
           'title'              => $data['title'],
           'name'               => $data['name'] ?? $user->first_name . $user->last_name,
           'mobile'             => $data['mobile'] ?? $user->mobile,
           'email'              => $data['email'] ?? null,
           'organization_id'    => $data['organization_id'] ?? null,
           'created_by'         => optional($user)->id,
           'send_type'          => $sendType,
           'receipt_type'       => $receiptType,
        ]);
    }
}
