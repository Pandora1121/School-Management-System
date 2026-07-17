<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ArchivedScope;


class Student extends Model
{
    protected $table = 'tbl_students';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'nis', 'name', 'img_url', 'id_class', 'id_major',
        'gender', 'birth_date', 'address', 'phone', 'status',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'id_class');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'id_major');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    protected static function booted(): void
{
    static::addGlobalScope(new ArchivedScope);
}
public function account()
{
    return $this->belongsTo(User::class, 'id_account');
}
}