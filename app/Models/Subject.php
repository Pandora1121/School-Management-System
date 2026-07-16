<?php

namespace App\Models;

use App\Models\Scopes\ArchivedScope;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'tbl_subjects';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'code', 'name', 'id_major', 'id_teacher', 'description',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ArchivedScope);
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'id_major');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id_teacher');
    }
}