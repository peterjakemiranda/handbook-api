<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'next_chapter'
    ];

    public function getNextChapterAttribute() 
    {
        return static::where('id', '>', $this->id)->max('id');
    }

    public function getPrevChapterAttribute() 
    {
        return static::where('id', '<', $this->id)->max('id');
    }
}
