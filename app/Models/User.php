<?php

namespace App\Models;

use App\Notifications\Auth\PasswordResetNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class User extends \TCG\Voyager\Models\User
{
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

  protected $dates = ['deleted_at'];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'surname',
    'phone',
    'position',
    'location',
    'status',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public $additional_attributes = ['full_name'];

  /**
   * The possible status a chat can be.
   */
  const STATUS = [
    'PUBLISHED' => 'PUBLISHED',
    'DRAFT' => 'DRAFT',
  ];

  const ROLES = [
    1 => 'admin',
    2 => 'manager',
    3 => 'employee',
  ];

  /**
   * Get the notification routing information for the given driver.
   *
   * @param  string  $driver
   * @return mixed
   */
  public function routeNotificationFor($driver)
  {
    $class = str_replace('\\', '.', get_class($this));

    Log::info('routeNotificationFor', [
      'class' => $class,
      'channel' => $driver
    ]);

    if (method_exists($this, $method = 'routeNotificationFor' . Str::studly($driver))) {
      return $this->{$method}();
    }

    switch ($driver) {
      case 'database':
        return $this->notifications();
      case 'mail':
        return $this->email;
      case 'nexmo':
        return $this->phone_number;
      case 'PusherPushNotifications':
        return 'user-' . $this->id;
    }


    return $class . '.' . $this->getKey();
  }

  public function delete()
  {
    Log::info("Run Delete method", [
      'tokens' => $this->tokens,
      'chats' => $this->chats,
      'messages' => $this->messages,
      'comments' => $this->comments,
      'posts' => $this->posts,
      'notes' => $this->notes,
      'role' => $this->role,
    ]);

    // Remove session tokens

    foreach ($this->tokens as $key => $value) {
      $value->delete();
    }

    // Drop user from chat

    DB::table('chat_user')->where('user_id', $this->id)->delete();

    // Soft-delete user messages

    foreach ($this->messages as $key => $value) {
      if (!$value->trashed()) {
        $value->delete();
      }
    }

    // Soft-delete user comments

    foreach ($this->comments as $key => $value) {
      if (!$value->trashed()) {
        $value->delete();
      }
    }

    // Soft-delete user posts

    foreach ($this->posts as $key => $value) {
      if (!$value->trashed()) {
        $value->delete();
      }
    }

    // Soft-delete user notes

    foreach ($this->notes as $key => $value) {
      if (!$value->trashed()) {
        $value->delete();
      }
    }

    // Call parent delete
    return parent::delete();
  }

  /**
   * Send a password reset email to the user
   */
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new PasswordResetNotification($token));
  }

  /**
   * User group chats
   * @return BelongsToMany
   */
  public function chats(): BelongsToMany
  {
    return $this->belongsToMany(Chat::class, 'chat_user', 'user_id', 'chat_id');
  }

  /**
   * Messages
   * @return HasMany
   */
  public function messages(): HasMany
  {
    return $this->hasMany(Message::class)->withTrashed();
  }

  public function notifications()
  {
    return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
  }

  /**
   * Comments
   * @return HasMany
   */
  public function comments(): HasMany
  {
    return $this->hasMany(Comment::class)->withTrashed();
  }

  /**
   * Posts
   * @return HasMany
   */
  public function posts(): HasMany
  {
    return $this->hasMany(Post::class, 'author_id')->withTrashed();
  }

  /**
   * Notes
   * @return HasMany
   */
  public function notes(): HasMany
  {
    return $this->hasMany(Note::class)->withTrashed();
  }

  /**
   * @return BelongsTo
   */
  public function role(): BelongsTo
  {
    return $this->belongsTo(Role::class);
  }

  /**
   * @return BelongsToMany
   */
  public function options(): BelongsToMany
  {
    return $this->belongsToMany(Option::class);
  }

  /**
   * @return BelongsToMany
   */
  public function unreadMessages(): BelongsToMany
  {
    return $this->belongsToMany(Message::class, 'message_reads', 'user_id', 'message_id')->where('seen', 0);
  }

  /**
   * Get the user's full name.
   *
   * @return string
   */
  public function getFullNameAttribute()
  {
    return "{$this->name} {$this->surname}";
  }
}
