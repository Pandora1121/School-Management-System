<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_users';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'last_login_time', 'username', 'email', 'password', 'name',
        'img_url', 'phone', 'role', 'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_user');
    }
    public function teacher()
{
    return $this->hasOne(Teacher::class, 'id_account');
}

public function student()
{
    return $this->hasOne(Student::class, 'id_account');
}
    
public function children()
{
    return $this->hasMany(\App\Models\ParentStudent::class, 'id_user');
}
}