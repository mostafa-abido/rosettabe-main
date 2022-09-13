<?php


namespace App\Actions;


use App\Models\Post;
use TCG\Voyager\Actions\AbstractAction;

class BatchApproveAction extends AbstractAction
{
  public function getTitle()
  {
    return 'Batch Approve Posts';
  }

  public function getIcon()
  {
    return 'voyager-check';
  }

  public function getPolicy()
  {
    return 'read';
  }

  public function getAttributes()
  {
    return [
      'class' => 'btn btn-sm btn-warning',
    ];
  }

  public function getDefaultRoute()
  {
    return route('notify-user', $this->data->id);
  }

  public function shouldActionDisplayOnDataType()
  {
    return $this->dataType->slug == 'posts';
  }

  public function massAction($ids, $comingFrom)
  {
    if (!$ids) {
      return redirect($comingFrom);
    }

    foreach ($ids as $id) {
      $post = Post::find($id);
      if ($post) {
        $post->update(['status' => 'PUBLISHED']);
      }
    }

    // Do something with the IDs
    return redirect($comingFrom);
  }
}
