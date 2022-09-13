<?php


namespace App\Actions;


use TCG\Voyager\Actions\AbstractAction;

class ViewMessagesAction extends AbstractAction
{
  public function getTitle()
  {
    return 'Messages';
  }

  public function getIcon()
  {
    return 'voyager-bubble';
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
    return route('voyager.messages.index', [
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
