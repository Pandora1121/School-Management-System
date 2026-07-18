<?php

namespace App\Models;

use App\Models\Scopes\ArchivedScope;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'tbl_exams';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'id_student', 'id_subject', 'id_class', 'id_teacher',
        'exam_type', 'score', 'note',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ArchivedScope);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'id_subject');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'id_class');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id_teacher');
    }
}