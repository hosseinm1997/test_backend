<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Enumerations\Ticket\TypeEnum;
use App\Http\Resources\TicketResource;
use App\Services\Ticket\TicketService;
use App\Http\Resources\FullTicketResource;
use App\Http\Requests\Ticket\createPeopleTicketRequest;
use Infrastructure\Interfaces\TicketRepositoryInterface;

class TicketController extends Controller
{
    public function index()
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return TicketResource::collection($repository->index());
    }

    public function getTicketsForOrganization()
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return TicketResource::collection($repository->getTicketsForOrganization());
    }

    public function show($ticketId)
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return new FullTicketResource($repository->show($ticketId));
    }

    public function createPeopleTicket(createPeopleTicketRequest $request)
    {
        $ticketService = new TicketService();

        return $ticketService->create(
            $request->all(),
            auth_user(),
            TypeEnum::PEOPLE,
            TypeEnum::ORGANIZATION
        );
    }
}
