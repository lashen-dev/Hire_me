<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Applicant extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'name',
        'skills',
        'experience_level',
        'address',
        'location',
        'phone',
        'user_id',
        'company_id',
        'image'
        
        
    ];

    // Define any relationships or additional methods here

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function jobs()
    {
        return $this->belongsToMany(Job::class,  'applications', 'applicant_id', 'job_id')
            ->withPivot('status', 'cv')
            ->withTimestamps();
    }        
}
