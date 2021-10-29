<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Enumerations\Ticket\TypeEnum;
use App\Http\Resources\TicketResource;
use App\Http\Resources\FullTicketResource;
use App\Http\Requests\Ticket\CreatePeopleTicketRequest;
use Infrastructure\Interfaces\TicketRepositoryInterface;
use App\Http\Requests\Ticket\SendTicketToManagementRequest;
use Infrastructure\Interfaces\Services\TicketServiceInterface;

class TicketController extends Controller
{
    public function index()
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return TicketResource::collection($repository->index());
    }

    public function getOrganizationTickets()
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return TicketResource::collection($repository->getOrganizationTickets());
    }

    public function getOrganizationTicket($ticketId)
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return new FullTicketResource($repository->getOrganizationTicket($ticketId));
    }

    public function show($ticketId)
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return new FullTicketResource($repository->show($ticketId));
    }

    public function createPeopleTicket(createPeopleTicketRequest $request)
    {
        /* @var TicketServiceInterface $service */
        $service = app(TicketServiceInterface::class);

        return $service->createTicket(
            $request->all(),
            auth_user(),
            TypeEnum::PEOPLE,
            TypeEnum::ORGANIZATION
        );
    }

    public function sendTicketToManagement(SendTicketToManagementRequest $request)
    {
        /* @var TicketServiceInterface $service */
        $service = app(TicketServiceInterface::class);

        return $service->createTicket(
            $request->all(),
            auth_user(),
            TypeEnum::ORGANIZATION,
            TypeEnum::MANAGEMENT
        );
    }
}
