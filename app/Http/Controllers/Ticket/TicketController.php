<?php

namespace App\Http\Controllers\Ticket;

use Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Ticket\CreateTicketRequest;
use Infrastructure\Interfaces\TicketRepositoryInterface;

class TicketController extends Controller
{
    public function index()
    {
        /* @var TicketRepositoryInterface $repository  */
        $repository = app(TicketRepositoryInterface::class);

        return $repository->index();
    }

    public function show($ticketId)
    {
        /* @var TicketRepositoryInterface $repository  */
        $repository = app(TicketRepositoryInterface::class);

        $ticket =$repository->show($ticketId);

        return [
            'id' => $ticket->id,
            'email' => $ticket->email,
            'title' => $ticket->title,
            'status' => $ticket->status,
            'mobile' => $ticket->mobile,
            'threads' => $ticket->threads,
            'priority' => $ticket->priority,
            'organization' => $ticket->organization
        ];
    }

    public function store(CreateTicketRequest $request)
    {
        /* @var TicketRepositoryInterface $ticketRepository  */
        $ticketRepository = app(TicketRepositoryInterface::class);

        try {
            DB::beginTransaction();

            $ticket = $ticketRepository->store($request->all(), auth()->user());

            DB::commit();

            return ['message' => 'تیکت با موفقیت ایجاد شد', 'result' => true, 'ticket_id' => $ticket->id];

        } catch (ValidationException | Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
