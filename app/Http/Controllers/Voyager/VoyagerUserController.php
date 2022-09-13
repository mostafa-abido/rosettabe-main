<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerUserController as BaseVoyagerUserController;

class VoyagerUserController extends BaseVoyagerUserController
{

  /**
   * POST BRE(A)D - Store data.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $role_id = intval($request->role_id);

    Log::info('VoyagerUserController', [
      'role_id' => $role_id
    ]);

    $chats = DB::table('chats')->where('auto_add_teammembers', true)->get(['id']);
    Log::info('VoyagerUserController', [
      'chats' => $chats
    ]);

    $slug = $this->getSlug($request);

    $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

    // Check permission
    $this->authorize('add', app($dataType->model_name));

    // Validate fields with ajax
    $val = $this->validateBread($request->all(), $dataType->addRows);

    if ($val->fails()) {
      return response()->json(['errors' => $val->messages()]);
    }

    if (!$request->has('_validate')) {
      $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

      if ($role_id !== 2) {
        foreach ($chats as $chat) {
          Log::info('VoyagerUserController', [
            'chat_id' => $chat->id,
            'id' => $data->id
          ]);

          DB::table('chat_user')->insert([
            'chat_id' => $chat->id,
            'user_id' => $data->id
          ]);
        }
      }

      event(new BreadDataAdded($dataType, $data));

      if ($request->ajax()) {
        return response()->json(['success' => true, 'data' => $data]);
      }

      return redirect()
        ->route("voyager.{$dataType->slug}.index")
        ->with([
          'message'    => __('voyager::generic.successfully_added_new') . " {$dataType->display_name_singular}",
          'alert-type' => 'success',
        ]);
    }
  }

  // POST BR(E)AD
  public function update(Request $request, $id)
  {
    $role_id = intval($request->role_id);

    Log::info('VoyagerUserController', [
      'role_id' => $role_id
    ]);

    $chats = DB::table('chats')->where('auto_add_teammembers', true)->get(['id']);
    Log::info('VoyagerUserController', [
      'chats' => $chats
    ]);

    if ($role_id !== 2) {


      foreach ($chats as $chat) {
        Log::info('VoyagerUserController', [
          'chat_id' => $chat->id,
          'id' => $id
        ]);

        DB::table('chat_user')->insert([
          'chat_id' => $chat->id,
          'user_id' => intval($id)
        ]);
      }
    } else {
      foreach ($chats as $chat) {
        DB::table('chat_user')
          ->where('chat_id', $chat->id)
          ->where('user_id', intval($id))
          ->delete();
      }
    }

    return parent::update($request, $id);
  }
}
