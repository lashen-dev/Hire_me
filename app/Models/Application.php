<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'applicant_id',
        'job_id',
        'status',
        'cv',
    ];

    // Define relationships
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    
}
