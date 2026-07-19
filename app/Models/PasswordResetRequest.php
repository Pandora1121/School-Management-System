<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $table = 'tbl_password_reset_requests';
    public $timestamps = false;

    protected $fillable = [
        'creation_time', 'update_time', 'create_id', 'update_id', 'archived',
        'id_user', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}