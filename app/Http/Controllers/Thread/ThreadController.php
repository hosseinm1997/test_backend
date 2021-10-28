<?php

namespace App\Http\Controllers\Thread;

use Throwable;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use App\Enumerations\FileCategoryEnums;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Thread\CreateThreadRequest;
use Infrastructure\Interfaces\ThreadRepositoryInterface;

class ThreadController extends Controller
{
    public function store(CreateThreadRequest $request)
    {
        /* @var ThreadRepositoryInterface $threadRepository  */
        $threadRepository = app(ThreadRepositoryInterface::class);
        $fileId = null;
        try {
            DB::beginTransaction();

            if ($request->hasFile('file')) {
                $dir = 'tickets/' . Hashids::encode($request->input('ticket_id'));
                $fileId = uploadFile(
                    $request->file('file'),
                    $dir,
                    FileCategoryEnums::THREAD_ATTACHMENT)['id'];
            }

            $thread = $threadRepository->store(
                $request->all(),
                auth_user(),
                $fileId,
                $request->input('ticket_id'),
                $request->input('send_type'),
            );

            DB::commit();

            return ['message' => 'ترد با موفقیت ایجاد شد', 'result' => true, 'thread_id' => $thread->id];

        } catch (ValidationException | Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
