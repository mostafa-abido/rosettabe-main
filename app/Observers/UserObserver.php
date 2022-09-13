<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\Auth\UserCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class UserObserver
{
  /**
   * Handle the User "created" event.
   *
   * @param  \App\Models\User  $user
   * @return void
   */
  public function created(User $user)
  {
    $user->notify(new UserCreatedNotification($user));
  }

  /**
   * Handle the User "updated" event.
   *
   * @param  \App\Models\User  $user
   * @return void
   */
  public function updated(User $user)
  {
    //
  }

  /**
   * Handle the User "deleted" event.
   *
   * @param  \App\Models\User  $user
   * @return void
   */
  public function deleted(User $user)
  {
    //
  }

  /**
   * Handle the User "restored" event.
   *
   * @param  \App\Models\User  $user
   * @return void
   */
  public function restored(User $user)
  {
    //
  }

  /**
   * Handle the User "force deleted" event.
   *
   * @param  \App\Models\User  $user
   * @return void
   */
  public function forceDeleted(User $user)
  {
    //
  }
}
