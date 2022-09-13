<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\Post\NewCommentNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Comment extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = ['deleted_at'];

  /**
   * The possible status a chat can be.
   */
  const STATUS = [
    'PUBLISHED',
    'BANNED',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'body',
    'post_id',
    'user_id',
  ];

  /**
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  /**
   * @return BelongsTo
   */
  public function post(): BelongsTo
  {
    return $this->belongsTo(Post::class, 'post_id', 'id');
  }

  public function save(array $options = []): bool
  {
    $post = $this->post;

    Log::info('save comment', [
      'post' => $post,
      'exists' => $this->exists
    ]);

    if (!$this->exists && $post) {
      $post->user->notify(new NewCommentNotification($this));
    }

    $status = parent::save();

    return $status;
  }
}
