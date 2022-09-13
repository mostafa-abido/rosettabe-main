<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\Chat\ChatNoteCreatedNotification;
use App\Notifications\Chat\ChatNoteUpdatedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class Note extends Model
{
  use HasFactory, SoftDeletes, Notifiable;

  protected $dates = ['deleted_at'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'description',
    'chat_id',
    'user_id',
  ];

  public function save(array $options = [])
  {
    parent::save();


    $chatMembers = Chat::find($this->chat_id)->members;

    if ($this->id) {
      $chatMembers->each(function ($member) {
        $member->notify(new ChatNoteUpdatedNotification($this));
      });
    } else {
      $chatMembers->each(function ($member) {
        $member->notify(new ChatNoteCreatedNotification($this));
      });
    }
  }

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
  public function chat()
  {
    return $this->belongsTo(Chat::class);
  }
}
