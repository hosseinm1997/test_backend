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
use Infrastructure\Interfaces\Services\TicketServiceInterface;

class TicketService implements TicketServiceInterface
{
    public function createTicket(array $data, User $user = null, int $sendType, int $receiptType)
    {
        /* @var TicketRepositoryInterface $ticketRepository */
        $ticketRepository = app(TicketRepositoryInterface::class);
        /* @var ThreadRepositoryInterface $threadRepository */
        $threadRepository = app(ThreadRepositoryInterface::class);

        try {
            DB::beginTransaction();

            $ticket = $ticketRepository->store($data, $user, $sendType, $receiptType);

            $fileId = $this->getFileId($data['file'], $ticket->id);

            $threadRepository->store($data, $user, $fileId, $ticket->id, $sendType);

            DB::commit();

            return ['message' => 'تیکت با موفقیت ایجاد شد', 'result' => true, 'ticket_id' => $ticket->id];

        } catch (ValidationException | Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function getFileId($file, $ticketId)
    {
        $fileId = null;
        if (!is_null($file)) {
            $dir = 'tickets/' . Hashids::encode($ticketId);

            $fileId = uploadFile($file, $dir, FileCategoryEnums::THREAD_ATTACHMENT)['id'];
        }

        return $fileId;
    }
}
