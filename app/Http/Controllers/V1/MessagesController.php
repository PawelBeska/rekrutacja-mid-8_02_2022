<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ProductCollection;
use App\Jobs\SendMessageJob;
use App\Models\Message;
use App\Models\Product;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MessagesController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Message::class, 'message');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'per_page' => ['nullable', 'integer']
        ]);

        return $this->successResponse(
            new ProductCollection(
                Product::paginate(Arr::get($data, 'per_page', 15))
            )
        );
    }


    /**
     * @param \App\Http\Requests\StoreMessageRequest $request
     * @param \App\Services\MessageService $messageService
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMessageRequest $request, MessageService $messageService): JsonResponse
    {
        $data = $request->validated();
        try {
            $message = $messageService->assignData([
                'title' => $data['title'],
                'author' => $data['author'],
                'content' => $data['content'],
            ])->getMessage();

            SendMessageJob::dispatch($message);
            return $this->successResponse('ok', ResponseAlias::HTTP_CREATED);
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(
                __('message.Something went wrong'),
                status: ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
