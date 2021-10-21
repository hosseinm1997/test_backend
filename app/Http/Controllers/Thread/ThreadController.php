<?php

namespace App\Http\Controllers\Thread;

use Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Thread\CreateThreadRequest;
use Infrastructure\Interfaces\ThreadRepositoryInterface;

class ThreadController extends Controller
{
    public function store(CreateThreadRequest $request)
    {
        /* @var ThreadRepositoryInterface $threadRepository  */
        $threadRepository = app(ThreadRepositoryInterface::class);

        try {
            DB::beginTransaction();

            $thread = $threadRepository->store($request->input('ticket_id'), $request->all());

            DB::commit();

            return ['message' => 'ترد با موفقیت ایجاد شد', 'result' => true, 'thread_id' => $thread->id];

        } catch (ValidationException | Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
