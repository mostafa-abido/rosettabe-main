<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
  public function list(Request $request)
  {
    $user = $request->user();
    return $user->notifications()->orderBy('read_at', 'DESC')->orderBy('created_at', 'DESC')->cursorPaginate(30);
  }

  public function read(Request $request)
  {
    $user = $request->user();
    $unreadNotifications = $user->unreadNotifications;

    return [
      'feed' => $unreadNotifications->filter(function ($value) {
        return $value->data['type'] === 'post' || $value->data['type'] === 'comment';
      })->isNotEmpty(),
      'chat' => $unreadNotifications->filter(function ($value) {
        return $value->data['type'] === 'message' || $value->data['type'] === 'note' || $value->data['type'] === 'chat';
      })->isNotEmpty(),
      "resources" => $unreadNotifications->filter(function ($value) {
        return $value->data['type'] === 'resources';
      })->isNotEmpty(),
      "colleagues" => $unreadNotifications->filter(function ($value) {
        return $value->data['type'] === 'users';
      })->isNotEmpty(),
    ];
  }

  public function store(Request $request)
  {
    $type = $request->type;

    if ($type === 'feed') {
      DB::table('notifications')
        ->where('notifiable_id', $request->user()->id)
        ->whereJsonContains('data->type', 'post')
        ->orWhereJsonContains('data->type', 'comment')
        ->update(['read_at' => now()]);
    } else if ($type === 'chats') {
      DB::table('notifications')
        ->where('notifiable_id', $request->user()->id)
        ->whereJsonContains('data->type', 'message')
        ->orWhereJsonContains('data->type', 'note')
        ->orWhereJsonContains('data->type', 'chat')
        ->update(['read_at' => now()]);
    }


    return 'ok';
  }
}
