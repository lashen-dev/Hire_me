<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];



    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function applicant()
    {
        return $this->hasOne(Applicant::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }



    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
    public function isCompany()
    {
        return $this->hasRole('company');
    }
    public function isApplicant()
    {
        return $this->hasRole('applicant');
    }
    public function isInCompeleteProfile()
    {
        $user = Auth::user();
        if ($user->role === 'applicant') {
            $applicant = Applicant::where('user_id', $user->id)->first();
            $fields = [
                'name',
                'skills',
                'experience_level',
                'address',
                'location',
                'phone',
            ];

            foreach ($fields as $field) {
                if (is_null($applicant->{$field})) {
                    return true;
                }

            }
            return false;
        }



        if ($user->role === 'company') {
            $company = Company::where('user_id', $user->id)->first();
            $fields = [
        'name',
        'location',
        'description',
        'phone',
        'address',
        'logo' ,
            ];
            foreach ($fields as $field) {
                if (is_null($company->{$field})) {
                    return true;
                }
            }
            return false;
        }
        
        return false; 
    }
    
}
