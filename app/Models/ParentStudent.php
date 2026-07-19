<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    protected $table = 'tbl_parent_students';
    public $timestamps = false;

    protected $fillable = ['creation_time', 'create_id', 'archived', 'id_user', 'id_student'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}