<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Applicant;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use HasFactory , Notifiable;
    protected $fillable = [
        'name',
        'logo',
        'location',
        'website',
        'description',
        'phone',
        'address',
        'user_id',

    ];

    // Define any relationships or additional methods here
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
    public function applications()
    {
        return $this->hasManyThrough(
            Application::class, // الـ Model النهائي اللي عايزه
            Job::class,         // الـ Model الوسيط
            'company_id',       // مفتاح الـ foreign في جدول jobs اللي بيربطه بالشركة
            'job_id',           // مفتاح الـ foreign في جدول applications اللي بيربطه بالوظيفة
            'id',               // المفتاح المحلي في جدول companies
            'id'                // المفتاح المحلي في جدول jobs
        );
    }
}
