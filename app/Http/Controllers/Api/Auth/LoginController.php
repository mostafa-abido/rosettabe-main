<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
  use SendsPasswordResetEmails;

  /**
   * Sanctum login endpoint
   *
   * @param LoginRequest $request
   * @return JsonResponse
   */
  public function login(LoginRequest $request): JsonResponse
  {
    // Check user in database
    $user = User::where('email', '=', $request->email)->first();
    if (!$user) {
      return response()
        ->json(['errors' => [1 => 'User not found']])
        ->setStatusCode(422);
    }

    // Check credentials
    if (!auth()->attempt($request->only('email', 'password'))) {
      return response()
        ->json(['errors' => [2 => 'Credentials not match']])
        ->setStatusCode(422);
    }

    $token = $user->createToken('auth_token')->plainTextToken;
    return response()->json([
      'user' => new UserResource($user),
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }

  /**
   * Remove token and logout
   *
   * @return string[]
   */
  public function logout($request)
  {
    Auth::logout();
    Auth::user()->tokens()->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return [
      'message' => 'Tokens Revoked'
    ];
  }

  /**
   * Return user by Bearer token header
   *
   * @param Request $request
   * @return UserResource
   */
  public function user(Request $request): UserResource
  {
    return new UserResource($request->user());
  }

  /**
   * Forgot password email form
   */
  public function forgot(Request $request): JsonResponse
  {
    $credentials = request()->validate(['email' => 'required|email']);
    if (!$credentials) {
      return response()
        ->json(['errors' => [2 => 'Credentials not match']])
        ->setStatusCode(422);
    }
    Password::sendResetLink($credentials);

    return response()->json(["message" => 'Reset password link sent on your email.']);
  }

  /**
   * Reset password form
   */
  public function reset(): JsonResponse
  {
    $credentials = request()->validate([
      'email' => 'required|email',
      'token' => 'required|string',
      'password' => 'required|string|confirmed'
    ]);

    $reset_password_status = Password::reset($credentials, function ($user, $password) {
      $user->forceFill([
        'password' => Hash::make($password)
      ])->setRememberToken(Str::random(60));

      $user->save();

      event(new PasswordReset($user));
    });

    if ($reset_password_status == Password::INVALID_TOKEN) {
      return response()->json([
        "errors" => [
          "email" => "Outdated reset password link"
        ]
      ]);
    }

    if ($reset_password_status == Password::PASSWORD_RESET) {
      return response()->json([
        "msg" => "Password has been successfully changed"
      ]);
    }

    if ($reset_password_status == Password::INVALID_USER) {
      return response()->json([
        "errors" => [
          "email" => "User email doesn't match"
        ]
      ]);
    }
  }
}
