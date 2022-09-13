<?php


namespace App\Actions;


use TCG\Voyager\Actions\AbstractAction;

class NotifySingleUserAction extends AbstractAction
{

  public function getTitle()
  {
    return 'Notify';

  }

  public function getPolicy()
  {
    return 'read';
  }

  public function getAttributes()
  {
    return [
      'class'   => 'btn btn-sm btn-primary',
      'data-id' => $this->data->{$this->data->getKeyName()},
      'id'      => 'delete-'.$this->data->{$this->data->getKeyName()},
    ];
  }

  public function shouldActionDisplayOnDataType()
  {
    return $this->dataType->slug == 'users';
  }

  public function getIcon()
  {
    return 'voyager-eye';
  }

  public function getDefaultRoute()
  {
    return route('notify-user', $this->data->id);
  }
}
