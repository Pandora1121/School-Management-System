<?php

namespace App\Models;

use App\Models\Scopes\ArchivedScope;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'tbl_teachers';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'nip', 'name', 'img_url', 'id_major', 'gender',
        'birth_date', 'address', 'phone', 'email', 'status',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ArchivedScope);
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'id_major');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function account()
{
    return $this->belongsTo(User::class, 'id_account');
}

public function classesAsWaliKelas()
{
    return $this->hasMany(SchoolClass::class, 'id_wali_kelas');
}
}