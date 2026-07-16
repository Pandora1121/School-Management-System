<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ArchivedScope;


class Major extends Model
{
    protected $table = 'tbl_majors';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'name', 'img_url', 'description',
    ];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'id_major');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_major');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    protected static function booted(): void
{
    static::addGlobalScope(new ArchivedScope);
}
public function teachers()
{
    return $this->hasMany(Teacher::class, 'id_major');
}
}