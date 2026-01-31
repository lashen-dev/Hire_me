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

    public function scopefilter($query, array $filters)
    {
        if ($filters['company_id'] ?? false) {
            $query->where('company_id', $filters['company_id']);
        }
        if ($filters['name'] ?? false) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if ($filters['skills'] ?? false) {
            $query->where('skills', 'like', '%' . $filters['skills'] . '%');
        }
    }
}
