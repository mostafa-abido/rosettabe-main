<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserForceDeleteController extends Controller
{
  public function forceDelete(int $id)
  {
    $user = User::where('id', $id);
    $user->forceDelete();
    return redirect()->back();
  }
}
