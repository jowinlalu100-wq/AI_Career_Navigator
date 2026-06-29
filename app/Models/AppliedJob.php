<?php
// app/Models/AppliedJob.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppliedJob extends Model
{
    protected $fillable = [
        'name',
        'email',
        'user_id',
        'resume_id',
        'job_title',
        'company_name',
        'job_location',
        'apply_link',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
    public function appliedJobs()
{
    return $this->hasMany(AppliedJob::class);
}
}