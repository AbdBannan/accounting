<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo("App\Models\Post");
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo("App\Models\User");
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany("App\Models\Reply","replyable");
    }

}
