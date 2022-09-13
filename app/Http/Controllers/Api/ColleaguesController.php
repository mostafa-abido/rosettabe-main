<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ColleaguesResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ColleaguesController extends Controller
{
  /**
   * Return all users with employee role.
   *
   * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
   */
  public function index()
  {
    $users = DB::table('users')->where('role_id', '!=', 2)->where('deleted_at', null)
      ->orderBy('name', 'asc')->limit(-1)->get();

    $users->transform(function($user) {
      return new ColleaguesResource($user);
    });

    return $users;
  }

  /**
   * Return user.
   *
   * @param User $user
   * @return ColleaguesResource
   */
  public function show(User $user)
  {
    return new ColleaguesResource($user);
  }
}
