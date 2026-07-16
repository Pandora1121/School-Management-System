<?php

namespace App\Models;

use App\Models\Scopes\ArchivedScope;
use Illuminate\Database\Eloquent\Model;

class ClassRoutine extends Model
{
    protected $table = 'tbl_class_routines';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'id_class', 'id_subject', 'id_teacher', 'day', 'start_time', 'end_time',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ArchivedScope);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'id_class');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'id_subject');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id_teacher');
    }
}