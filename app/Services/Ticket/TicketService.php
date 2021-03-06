<?php

namespace App\Services\Ticket;

use Throwable;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Enumerations\Ticket\TypeEnum;
use App\Enumerations\FileCategoryEnums;
use Illuminate\Validation\ValidationException;
use Infrastructure\Interfaces\ThreadRepositoryInterface;
use Infrastructure\Interfaces\TicketRepositoryInterface;
use Infrastructure\Interfaces\Services\TicketServiceInterface;

class TicketService implements TicketServiceInterface
{
    public function createTicket(array $data, User $user = null, int $sendType, int $receiptType): array
    {
        /* @var TicketRepositoryInterface $ticketRepository */
        $ticketRepository = app(TicketRepositoryInterface::class);
        /* @var ThreadRepositoryInterface $threadRepository */
        $threadRepository = app(ThreadRepositoryInterface::class);
        $fileId = null;
        try {
            DB::beginTransaction();

            $ticket = $ticketRepository->store($data, $user, $sendType, $receiptType);
;
            if (isset($data['file']))
                $fileId = $this->getFileId($data['file'], $ticket->id);

            $threadRepository->store($data, $user, $fileId, $ticket->id, $sendType);

            DB::commit();

            $this->sendSms($ticket->mobile, $sendType, $receiptType);

            return ['message' => 'تیکت با موفقیت ایجاد شد', 'result' => true, 'ticket_id' => $ticket->id];

        } catch (ValidationException | Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function createThread(array $data, User $user = null, $ticketId, $sendType): array
    {
        /* @var ThreadRepositoryInterface $threadRepository  */
        $threadRepository = app(ThreadRepositoryInterface::class);
        $fileId = null;
        try {
            DB::beginTransaction();

            if (isset($data['file']))
                $fileId = $this->getFileId($data['file'], $ticketId);

            $thread = $threadRepository->store($data, $user, $fileId, $ticketId, $sendType);

            DB::commit();

            return ['message' => 'ترد با موفقیت ایجاد شد', 'result' => true, 'thread_id' => $thread->id];

        } catch (ValidationException | Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function getFileId($file, int $ticketId)
    {
        $dir = 'tickets/' . Hashids::encode($ticketId);

        return uploadFile($file, $dir, FileCategoryEnums::THREAD_ATTACHMENT)['id'];
    }

    private function sendSms($mobile, int $sendType, int $receiptType)
    {
        if ($sendType == TypeEnum::ORGANIZATION && $receiptType == TypeEnum::MANAGEMENT) {
            sendSmsByPattern($mobile, config('pattern.successful_ticket'), [
                'time' => (new Verta())->format('Y/m/d H:i:s')
            ]);
        }
    }
}
