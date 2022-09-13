<?php


namespace App\Actions;


use TCG\Voyager\Actions\AbstractAction;

class ViewCommentsAction extends AbstractAction
{
  public function getTitle()
  {
    return 'Comments';
  }

  public function getIcon()
  {
    return 'voyager-forward';
  }

  public function getPolicy()
  {
    return 'read';
  }

  public function getAttributes()
  {
    return [
      'class' => 'btn btn-sm btn-info',
    ];
  }

  public function getDefaultRoute()
  {
    return route('voyager.comments.index', [
      'key' => 'post_id',
      'filter' => 'equals',
      's' => $this->data->id
    ]);
  }

  public function shouldActionDisplayOnDataType()
  {
    return $this->dataType->slug == 'posts';
  }
}
