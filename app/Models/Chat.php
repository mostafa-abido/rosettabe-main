<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The possible status a chat can be.
   */
  const STATUS = [
    'PUBLISHED',
    'DRAFT',
    'PENDING',
    'BANNED'
  ];

  protected $dates = ['deleted_at'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'name',
  ];

  /**
   * @return BelongsToMany
   */
  public function members(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'chat_user', 'chat_id');
  }

  /**
   * @return HasMany
   */
  public function notes(): HasMany
  {
    return $this->hasMany(Note::class);
  }

  /**
   * @return HasMany
   */
  public function messages(): HasMany
  {
    return $this->hasMany(Message::class);
  }
}
