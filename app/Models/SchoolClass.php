<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ArchivedScope;


class SchoolClass extends Model
{
    protected $table = 'tbl_classes';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'code', 'name', 'id_major', 'description',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'id_major');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_class');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    protected static function booted(): void
{
    static::addGlobalScope(new ArchivedScope);
}
}