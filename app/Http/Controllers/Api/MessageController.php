<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Notifications\Message\MessageCreatedNotification;
use App\Models\MessageReads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
  public function show(Request $request, $id)
  {
    if ($request->query('unread')) {
      return MessageResource::collection(
        Chat::find($id)->messages->filter(function ($message) {
          return $message->unreadMessages->contains('id', Auth()->id());
        })
      );
    }

    return MessageResource::collection(
      Message::ofChatId($id)->cursorPaginate(30)
    );
  }

  public function showUnread(Request $request)
  {
    return MessageResource::collection(Message::where('chat_id', $request->message)->orderBy('id', 'desc')->cursorPaginate(30));
  }

  public function store(Request $request)
  {
    $message = Message::create([
      'message' => $request->message,
      'chat_id' => $request->chat_id,
      'user_id' => $request->user()->id,
      'status' => 'published'
    ]);

    Log::info('store', [
      'message' => $message,
      'notification' => new MessageCreatedNotification($message)
    ]);
    // Notify chat users about new message
    foreach ($message->chat->members as $member) {
      if ($member->id != $message->user_id) {
        $member->notify(new MessageCreatedNotification($message));
      }
    }

    if ($request->has('files')) {
      foreach ($request->post('files') as $file) {
        $message->attachments()->create([
          'filename' => $file,
          'path' => $file,
          'user_id' => $request->user()->id,
        ]);
      }
    }

    // Set seen status 0 for chat member
    foreach ($message->chat->members as $member) {
      if ($member->id != $message->user_id) {
        MessageReads::create(['message_id' => $message->id, 'user_id' => $member->id, 'seen' => 0]);
      }
    }

    return new MessageResource($message->load('chat', 'user', 'attachments'));
  }

  /**
   * @param int $id
   * @return Model|Builder|JsonResponse|object
   */
  public function messageSetSeen(Request $request)
  {
    $ids = $request->ids;
    MessageReads::whereIn('message_id', $ids)->where('user_id', $request->user()->id)->delete();
    return response()->json(['status' => true]);
  }
}
