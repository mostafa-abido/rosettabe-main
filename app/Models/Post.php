<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Query\Builder;

use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use TCG\Voyager\Facades\Voyager;

use Illuminate\Support\Facades\Notification;
use App\Notifications\Post\PostPublishedNotification;
use App\Notifications\Post\PostSubmittedNotification;

class Post extends \TCG\Voyager\Models\Post
{

  use HasFactory, SoftDeletes, Notifiable;

  public const PUBLISHED = 'PUBLISHED';

  protected $dates = ['deleted_at'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id',
    'author_id',
    'title',
    'body',
    'status',
    'link',
    'attachment_link'
  ];

  public function save(array $options = [])
  {
    // If no author has been assigned, assign the current user's id as the author of the post
    if (!$this->author_id && Auth::user()) {
      $this->author_id = Auth::user()->getAuthIdentifier();
    }

    $post = Post::find($this->id);
    $admins = User::where('role_id', 1)->get(['id', 'email']);
    $employees = User::where('role_id', '!=', 2)->get(['id']);
    // $familyMembers = User::where('role_id', '=', 2)->get(['id']);

    if ($post) {
      Log::info('Post Updated', [
        'post' => $post,
        'status' => $this->status,
        'user-id' => $this->author_id,
        'options' => $options
      ]);

      // Notify post author that post was published
      if ($post->status !== Post::PUBLISHED && $this->status === Post::PUBLISHED) {
        Notification::send($this->user, new PostPublishedNotification($this));
      }
    } else {
      Log::info('Post Created', [
        'post' => $this,
        'admins' => $admins,
        'options' => $options
      ]);

      if (!$this->exists) {
        // Notify admins that post was submitted
        foreach ($admins as $admin) {
          $admin->notify(new PostSubmittedNotification($this));
        }
      }
    }

    $res = parent::save();

    if ($this->required) {
      foreach ($employees as $user) {
        $user_id = $user->id;
        $this->userReactions()->updateOrCreate([
          'reactable_id' => $this->id,
          'user_id' => $user_id
        ], [
          'user_id' => $user_id
        ]);
      };
    }

    return $res;
  }

  public function authorId()
  {
    return $this->belongsTo(Voyager::modelClass('User'), 'author_id', 'id');
  }

  /**
   * @return BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class, 'author_id', 'id');
  }


  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\MorphMany
   */
  public function reactions()
  {
    return $this->morphMany(UserActionReaction::class, "reactable");
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function userReactions()
  {
    return $this->hasMany(\App\Models\UserActionReaction::class, 'reactable_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\MorphMany
   */
  public function likes()
  {
    return $this->morphMany(Like::class, "likeable");
  }

  public function isLikedByAuthUser(): bool
  {
    $like = $this->likes()->whereUserId(Auth::id())->first();
    return !is_null($like);
  }

  public function isReactedByAuthUser(): bool
  {
    $reacted = $this->reactions->firstWhere('user_id', Auth::id());

    if ($reacted) {
      return !!($reacted->reacted);
    } else {
      return false;
    }
  }

  /**
   * Scope a query to only action required post scopes.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   *
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeActionRequired(Builder $query): Builder
  {
    return $query->where('required', '=', true);
  }
}
