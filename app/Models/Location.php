<?php

namespace App\Models;

use App\Policies\V1\Admin\LocationPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuthor;

#[UsePolicy(LocationPolicy::class)]
class Location extends Model
{
    use SoftDeletes, HasAuthor;

    protected $fillable = [
        'author_id',
        'short_address',
        'address',
        'description',
        'latitude',
        'longitude',
        'is_active',
        'working_hours',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class);
    }
}
