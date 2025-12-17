<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'company_id',
        'details',
        'location',
        'salary',
        'type',
        'is_available',
        
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function applicants()
    {
        return $this->belongsToMany(Applicant::class,'applications', 'job_id', 'applicant_id')
            ->withPivot('status', 'cv');
    }
    
    protected static function booted()
    {
        static::addGlobalScope('available', function ($query) {
            $query->where('is_available', true);
        });
    }

    public function scopeSearch($query, array $filters)
    {
        if ($filters['title'] ?? false) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }
        if ($filters['location'] ?? false) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }
        if ($filters['salary'] ?? false) {
            $query->where('salary', '>=', $filters['salary']);
        }
    }    

    
    

}
