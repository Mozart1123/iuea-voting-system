<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'faculty_restriction', 'is_active', 'status', 'start_time', 'end_time'];

    public function getTotalVotesAttribute()
    {
        $realVotes = $this->votes_count ?? $this->votes()->count();
        return $realVotes + $this->candidates()->sum('manual_votes');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
