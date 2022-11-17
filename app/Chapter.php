<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $table = 'chapters';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description',
    ];

    protected $appends = [
        'prev_chapter',
        'next_chapter',
        'access_count',
        'is_answered',
    ];

    public function getNextChapterAttribute() 
    {
        return static::where('id', '>', $this->id)->max('id');
    }

    public function getPrevChapterAttribute() 
    {
        return static::where('id', '<', $this->id)->max('id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class, 'chapter_id');
    }

    public function getAccessCountAttribute()
    {
        return $this->answers()->count();
    }

    public function getIsAnsweredAttribute()
    {
        return $this->answers()->where('student_id', auth()->id())->exists() ? 1 : 0;
    }
}
