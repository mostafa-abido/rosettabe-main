<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
  /**
   * Return all users with employee role.
   *
   * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
   */
  public function index()
  {
    $users = User::where('role_id', '!=', 2)
      ->orderBy('surname', 'asc')
      ->orderBy('name', 'asc')
      ->paginate(15);

    $users->transform(function($user) {
      return new UserResource($user);
    });

    return $users;
  }

  /**
   * Return user.
   *
   * @param User $user
   * @return UserResource
   */
  public function show(User $user)
  {
    return new UserResource($user);
  }
}
