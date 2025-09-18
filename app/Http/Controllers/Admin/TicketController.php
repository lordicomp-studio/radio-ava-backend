<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ChatPriorityEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TicketStoreAndUpdateRequest;
use App\Http\Resources\Admin\Tickets\TicketIndexResource;
use App\Http\Resources\Admin\Tickets\TicketShowResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TicketController extends Controller
{

    public function index(): \Inertia\Response
    {
        $allPriorities = ChatPriorityEnum::options();
        $tickets = TicketIndexResource::collection(
            Chat::with(['sender', 'receiver', 'sender.profilePhoto', 'receiver.profilePhoto'])
                ->latest()->paginate(10)
        )->resource;

        return Inertia::render('Admin/Tickets/Index', [
            'allPriorities' => $allPriorities,
            'data' => json_decode(json_encode($tickets)), // the json encode and decode is for the resource to work correctly.
            'baseLink' => '/admin/tickets/indexDataApi',
            'type' => 'ticket',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $tickets = TicketIndexResource::collection(
            Chat::with(['sender', 'receiver', 'sender.profilePhoto', 'receiver.profilePhoto'])
                ->where('subject', 'LIKE', "%$searchText%")
                ->latest()->paginate(10)
        )->resource;

        return $tickets;
    }

    public function store(TicketStoreAndUpdateRequest $request)
    {
        $fields = $request->safe()->all();

        Chat::create($fields);

        return response(null, 200);
    }

    public function show(int $chatId)
    {
        $chat = new TicketShowResource(
            Chat::where('id', $chatId)
                ->with(['sender', 'receiver', 'messages', 'messages.sender', 'messages.sender.profilePhoto'])
                ->first()
        );

        return Inertia::render('Admin/Tickets/Show', [
            'chat' => $chat->toArray(request()),
            'type' => 'ticket',
        ]);
    }

    public function showDataApi(int $chatId)
    {
        $chat = new TicketShowResource(
            Chat::where('id', $chatId)
                ->with(['sender', 'receiver', 'messages', 'messages.sender', 'messages.sender.profilePhoto'])
                ->first()
        );

        return $chat;
    }

    public function update(TicketStoreAndUpdateRequest $request, int $chatId)
    {
        $fields = $request->safe()->all();

        Chat::find($chatId)->update($fields);

        return response(null, 200);
    }

    public function destroy(int $chatId)
    {
        // can't do route-model-binding because the resource
        Chat::find($chatId)->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Chat::destroy($request->toArray());
        return response(null, 200);
    }

}
