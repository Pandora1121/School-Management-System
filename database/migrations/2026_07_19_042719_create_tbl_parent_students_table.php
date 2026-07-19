<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_parent_students', function (Blueprint $table) {
            $table->id();
            $table->dateTime('creation_time')->nullable();
            $table->unsignedBigInteger('create_id')->nullable();
            $table->tinyInteger('archived')->default(0);

            $table->unsignedBigInteger('id_user'); // akun orang tua
            $table->unsignedBigInteger('id_student');

            $table->foreign('id_user')->references('id')->on('tbl_users');
            $table->foreign('id_student')->references('id')->on('tbl_students');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_parent_students');
    }
};