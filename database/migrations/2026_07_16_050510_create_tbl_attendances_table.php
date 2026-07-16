<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_attendances', function (Blueprint $table) {
            $table->id();
            $table->dateTime('creation_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->unsignedBigInteger('create_id')->nullable();
            $table->unsignedBigInteger('update_id')->nullable();
            $table->tinyInteger('archived')->default(0);
            $table->unsignedBigInteger('id_user')->nullable();

            $table->unsignedBigInteger('id_student');
            $table->unsignedBigInteger('id_class');
            $table->date('date');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpa']);
            $table->text('note')->nullable();

            $table->foreign('id_user')->references('id')->on('tbl_users');
            $table->foreign('id_student')->references('id')->on('tbl_students');
            $table->foreign('id_class')->references('id')->on('tbl_classes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_attendances');
    }
};