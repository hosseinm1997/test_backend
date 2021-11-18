<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Enumerations\Ticket\TypeEnum;
use App\Http\Resources\TicketResource;
use App\Http\Resources\FullTicketResource;
use Infrastructure\Interfaces\TicketRepositoryInterface;
use Infrastructure\Interfaces\Services\TicketServiceInterface;
use App\Http\Requests\Ticket\SendPeopleTicketToOrganizationRequest;
use App\Http\Requests\Ticket\SendManagementTicketToOrganizationRequest;
use App\Http\Requests\Ticket\SendOrganizationTicketToManagementRequest;
use App\Http\Requests\Ticket\SendDevelopManagementTicketToManagementRequest;

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

    public function sendPeopleTicketToOrganization(SendPeopleTicketToOrganizationRequest $request)
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

    public function sendOrganizationTicketToManagement(SendOrganizationTicketToManagementRequest $request)
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

    public function sendManagementTicketToOrganization(SendManagementTicketToOrganizationRequest $request)
    {
        /* @var TicketServiceInterface $service */
        $service = app(TicketServiceInterface::class);

        return $service->createTicket(
            $request->all(),
            auth_user(),
            TypeEnum::MANAGEMENT,
            TypeEnum::ORGANIZATION
        );
    }

    public function sendDevelopManagementTicketToManagement(SendDevelopManagementTicketToManagementRequest $request)
    {
        /* @var TicketServiceInterface $service */
        $service = app(TicketServiceInterface::class);

        return $service->createTicket(
            $request->all(),
            auth_user(),
            TypeEnum::DEVELOP_MANAGEMENT,
            TypeEnum::MANAGEMENT
        );
    }
}
