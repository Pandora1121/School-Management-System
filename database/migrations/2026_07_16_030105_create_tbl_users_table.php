<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->id();
            $table->dateTime('creation_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->unsignedBigInteger('create_id')->nullable();
            $table->unsignedBigInteger('update_id')->nullable();
            $table->tinyInteger('archived')->default(0);

            $table->dateTime('last_login_time')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('img_url')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('role')->comment('1=Super Admin,2=Admin,3=Guru,4=Siswa');
            $table->tinyInteger('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};