<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $table = 'questions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question', 'options', 'answer'
    ];

    public function chapter() : BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }
}
