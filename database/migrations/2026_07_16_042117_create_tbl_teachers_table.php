<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_teachers', function (Blueprint $table) {
            $table->id();
            $table->dateTime('creation_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->unsignedBigInteger('create_id')->nullable();
            $table->unsignedBigInteger('update_id')->nullable();
            $table->tinyInteger('archived')->default(0);
            $table->unsignedBigInteger('id_user')->nullable();

            $table->string('nip')->unique();
            $table->string('name');
            $table->string('img_url')->nullable();
            $table->unsignedBigInteger('id_major')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->foreign('id_user')->references('id')->on('tbl_users');
            $table->foreign('id_major')->references('id')->on('tbl_majors');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_teachers');
    }
};