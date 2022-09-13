<?php


namespace App\Actions;


use TCG\Voyager\Actions\AbstractAction;

class ViewResponsesAction extends AbstractAction
{
  public function getTitle()
  {
    return 'AR Responses';
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
      'class' => 'btn btn-sm btn-info ' . ($this->data->required == '1' ? '' : 'hidden'),
    ];
  }

  public function getDefaultRoute()
  {
    return route('voyager.user-action-reactions.index', [
      'key' => 'reactable_id',
      'filter' => 'equals',
      's' => $this->data->id
    ]);
  }

  public function shouldActionDisplayOnDataType()
  {
    return $this->dataType->slug == 'posts';
  }
}
