<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, FieldsWithFilesObservable;

    // BM> files for the fields to be in synch
    public $fieldsWithFile = [
        'user_img' => ['filePath' => 'public/users', 'fileDriver' => 'public', 'fileRequired' => false]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
	    'first_name',
        'last_name',
        'email',
	    'password',
        'user_img'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guarded = ['id'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getCapabilitiesAttribute()
    { // so capabilities could be called as dynamic property, even within (json) resources
        return $this->capabilities();
    }

    public function capabilities()
    {
        $roles = $this->roles;
        $perms = new Collection();
        foreach($roles as $role) {
            $perms = $perms->merge($role->permissions->pluck('name')); //merge collections of permissions for each role
        }
        return $perms->unique();
    }

    public function hasAccess($permission)
    {
        return $this->capabilities()->contains($permission);
    }
}
