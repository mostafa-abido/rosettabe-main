<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'resource_id'
    ];

    /**
     * Resource attachments.
     *
     * @return HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ResourceAttachment::class);
    }

    /**
     * @return HasMany
     */
    public function folders(): HasMany
    {
        return $this->hasMany(static::class, 'resource_id');
    }
}
