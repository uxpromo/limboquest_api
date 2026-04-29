<?php

namespace App\Models;

use App\Policies\V1\Admin\CommentPolicy;
use App\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(CommentPolicy::class)]
class Comment extends Model
{
    use SoftDeletes, HasAuthor;

    protected $fillable = [
        'author_id',
        'commentable_type',
        'commentable_id',
        'text',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
