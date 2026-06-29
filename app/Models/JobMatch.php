<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobMatch extends Model
{
    protected $fillable=[
        'user_id',
        'resume_id',
        'job_title',
        'company',
        'match_score'
    ];
}
