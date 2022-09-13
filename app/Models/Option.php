<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Option extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'poll_id'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return bool
     */
    public function isVotedByAuthUser(): bool
    {
        return (bool)$this->users()->whereUserId(auth()->user()->id)->first();
    }
}
