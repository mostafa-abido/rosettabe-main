<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class AdminUserNotificationsController extends Controller
{
  public function notifyUser(User $user)
  {
    $status = Password::sendResetLink(['email' => $user->email]);

    return redirect()->back();
  }
}
