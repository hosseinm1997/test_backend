<?php

namespace App\Http\Controllers\Thread;

use App\Http\Controllers\Controller;
use App\Enumerations\Ticket\TypeEnum;
use App\Http\Requests\Thread\CreateThreadRequest;
use Infrastructure\Interfaces\Services\TicketServiceInterface;

class ThreadController extends Controller
{
    public function store(CreateThreadRequest $request)
    {
        /* @var TicketServiceInterface $service */
        $service = app(TicketServiceInterface::class);

        return $service->createThread(
            $request->all(),
            auth_user(),
            $request->input('ticket_id'),
            TypeEnum::ORGANIZATION
        );
    }
}
