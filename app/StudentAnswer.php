<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAnswer extends Model
{
    protected $table = 'student_answers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function student() : BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function chapter() : BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
