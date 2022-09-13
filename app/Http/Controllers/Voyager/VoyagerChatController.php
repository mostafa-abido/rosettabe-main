<?php

namespace App\Http\Controllers\Voyager;

use App\Models\Chat;
use App\Notifications\Chat\ChatAddedNotification;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

use TCG\Voyager\Http\Controllers\VoyagerBaseController as BaseVoyagerBaseController;

class VoyagerChatController extends BaseVoyagerBaseController
{
  use BreadRelationshipParser;

  // POST BR(E)AD
  public function update(Request $request, $id)
  {
    $slug = $this->getSlug($request);

    $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

    // Compatibility with Model binding.
    $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

    $model = app($dataType->model_name);
    if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
      $model = $model->{$dataType->scope}();
    }
    if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
      $data = $model->withTrashed()->findOrFail($id);
    } else {
      $data = $model->findOrFail($id);
    }

    $currentMembers = Chat::find($id)->members;
    $res = parent::update($request, $id);
    $updatedMembers = Chat::find($id)->members;

    $diffMembers = $updatedMembers->diff($currentMembers);

    $diffMembers->each(function ($user) use ($data) {
      $user->notify(new ChatAddedNotification($data));
    });

    return $res;
  }
}
