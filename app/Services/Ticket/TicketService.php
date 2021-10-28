<?php

namespace App\Services\Ticket;

use Throwable;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Enumerations\FileCategoryEnums;
use Illuminate\Validation\ValidationException;
use Infrastructure\Interfaces\ThreadRepositoryInterface;
use Infrastructure\Interfaces\TicketRepositoryInterface;

class TicketService
{
    public function create(array $data, User $user = null, int $sendType, int $receiptType)
    {
        /* @var TicketRepositoryInterface $ticketRepository */
        $ticketRepository = app(TicketRepositoryInterface::class);
        /* @var ThreadRepositoryInterface $threadRepository */
        $threadRepository = app(ThreadRepositoryInterface::class);

        $fileId = null;
        try {
            DB::beginTransaction();

            $ticket = $ticketRepository->store($data, $user, $sendType, $receiptType);

            if (!is_null($data['file'])) {
                $dir = 'tickets/' . Hashids::encode($ticket->id);
                $fileId = uploadFile(
                    $data['file'],
                    $dir,
                    FileCategoryEnums::THREAD_ATTACHMENT)['id'];
            }

            $threadRepository->store($data, $user, $fileId, $ticket->id, $sendType);

            DB::commit();

            return ['message' => 'تیکت با موفقیت ایجاد شد', 'result' => true, 'ticket_id' => $ticket->id];

        } catch (ValidationException | Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
