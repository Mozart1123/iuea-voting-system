<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voter extends Model
{
    protected $fillable = [
        'registration_number', 
        'full_name', 
        'ip_address', 
        'user_agent', 
        'voted_at'
    ];

    public function choices(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
