<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends Controller
{
  /**
   * @param Request $request
   * @return AnonymousResourceCollection
   */
  public function index(Request $request): AnonymousResourceCollection
  {
    $role_id = $request->user()->role->id;

    if ($role_id == 1) {
      return ChatResource::collection(Chat::all());
    } else {
      return ChatResource::collection($request->user()->chats()->get());
    }
  }

  /**
   * @param Chat $chat
   * @return ChatResource
   */
  public function show(Chat $chat): ChatResource
  {
    return new ChatResource($chat->load('messages.user'));
  }
}
