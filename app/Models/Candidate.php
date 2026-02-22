<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'faculty',
        'student_class',
        'biography',
        'photo_path',
        'position_number',
        'status',
        'manual_votes'
    ];

    protected $appends = ['total_votes'];

    public function getTotalVotesAttribute()
    {
        $realVotes = $this->votes_count ?? $this->votes()->count();
        return $realVotes + ($this->manual_votes ?? 0);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
