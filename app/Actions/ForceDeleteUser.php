<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ForceDeleteUser extends AbstractAction
{
    public function getTitle()
    {
        return 'Force Delete';
    }

    public function getIcon()
    {
        return 'voyager-trash';
    }

    public function getPolicy()
    {
        return 'restore';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-danger pull-right',
        ];
    }

    public function shouldActionDisplayOnDataType()
    {
      return $this->dataType->slug == 'users';
    }

    public function getDefaultRoute()
    {
      return route('force-delete-user', $this->data->id);
    }
}
