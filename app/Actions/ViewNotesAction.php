<?php


namespace App\Actions;


use TCG\Voyager\Actions\AbstractAction;

class ViewNotesAction extends AbstractAction
{
  public function getTitle()
  {
    return 'Notes';
  }

  public function getIcon()
  {
    return 'voyager-receipt';
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
    return route('voyager.notes.index', [
      'key' => 'chat_id',
      'filter' => 'equals',
      's' => $this->data->id
    ]);
  }

  public function shouldActionDisplayOnDataType()
  {
    return $this->dataType->slug == 'chats';
  }
}
