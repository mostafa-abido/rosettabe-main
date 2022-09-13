<?php


namespace App\Actions;


use App\Models\User;
use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Password;

class NotifyUserWithCredsAction extends AbstractAction
{
  public function getTitle()
  {
    return 'Resend Password';
  }

  public function getIcon()
  {
    return 'voyager-mail';
  }

  public function getPolicy()
  {
    return 'read';
  }

  public function getAttributes()
  {
    return [
      'class' => 'btn btn-sm btn-primary',
    ];
  }

  public function getDefaultRoute()
  {
    return route('notify-user', $this->data->id);
  }

  public function shouldActionDisplayOnDataType()
  {
    return $this->dataType->slug == 'users';
  }

  public function massAction($ids, $comingFrom)
  {
    if (!$ids) {
      return redirect($comingFrom);
    }

    foreach ($ids as $id) {
      $user = User::find($id);
      if ($user) {
        Password::sendResetLink(['email' => $user->email]);
      }
    }

    // Do something with the IDs
    return redirect($comingFrom);
  }
}
