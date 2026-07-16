<?php

namespace App\Models;

use App\Models\Scopes\ArchivedScope;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'tbl_attendances';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'id_student', 'id_class', 'date', 'status', 'note',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ArchivedScope);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'id_class');
    }
}