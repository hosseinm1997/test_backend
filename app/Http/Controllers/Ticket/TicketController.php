<?php

namespace App\Http\Controllers\Ticket;

use Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Enumerations\FileCategoryEnums;
use App\Http\Resources\FullTicketResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Ticket\CreateTicketRequest;
use Infrastructure\Interfaces\ThreadRepositoryInterface;
use Infrastructure\Interfaces\TicketRepositoryInterface;
use Vinkla\Hashids\Facades\Hashids;

class TicketController extends Controller
{
    public function index()
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return TicketResource::collection($repository->index());
    }

    public function show($ticketId)
    {
        /* @var TicketRepositoryInterface $repository */
        $repository = app(TicketRepositoryInterface::class);

        return new FullTicketResource($repository->show($ticketId));
    }

    public function store(CreateTicketRequest $request)
    {
        /* @var TicketRepositoryInterface $ticketRepository */
        $ticketRepository = app(TicketRepositoryInterface::class);
        /* @var ThreadRepositoryInterface $threadRepository */
        $threadRepository = app(ThreadRepositoryInterface::class);

        $fileId = null;
        $user = auth()->user();

        try {
            DB::beginTransaction();

            $ticket = $ticketRepository->store($request->all(), $user);
            if ($request->hasFile('file')) {
                $dir = 'tickets/' . Hashids::encode($ticket->id);
                $fileId = uploadFile(
                    $request->file('file'),
                    $dir,
                    FileCategoryEnums::THREAD_ATTACHMENT)['id'];
            }

            $threadRepository->store($ticket->id, $fileId, $request->all(), $user);

            DB::commit();

            return ['message' => 'تیکت با موفقیت ایجاد شد', 'result' => true, 'ticket_id' => $ticket->id];

        } catch (ValidationException | Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
