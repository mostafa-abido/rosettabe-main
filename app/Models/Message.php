<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Message extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = ['deleted_at'];

  /**
   * The possible status a chat can be.
   */
  public const STATUS = [
    'PUBLISHED' => 'PUBLISHED',
    'BANNED' => 'BANNED'
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'chat_id',
    'user_id',
    'message',
    'status'
  ];

  /**
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * @return BelongsTo
   */
  public function chat(): BelongsTo
  {
    return $this->belongsTo(Chat::class);
  }

  /**
   * @return MorphMany
   */
  public function attachments(): MorphMany
  {
    return $this->morphMany(Attachment::class, "attachable");
  }

  /**
   * @return BelongsToMany
   */
  public function unreadMessages(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'message_reads', 'message_id', 'user_id')->where('seen', '=', 0);
  }

  /**
   * Scope a query to chat messages scopes.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   *
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeOfChatId($query, $chat_id)
  {
    return $query->where('chat_id', $chat_id)->orderBy('id', 'desc');
  }
}
