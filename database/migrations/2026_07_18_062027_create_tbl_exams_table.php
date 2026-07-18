<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_exams', function (Blueprint $table) {
            $table->id();
            $table->dateTime('creation_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->unsignedBigInteger('create_id')->nullable();
            $table->unsignedBigInteger('update_id')->nullable();
            $table->tinyInteger('archived')->default(0);
            $table->unsignedBigInteger('id_user')->nullable();

            $table->unsignedBigInteger('id_student');
            $table->unsignedBigInteger('id_subject');
            $table->unsignedBigInteger('id_class');
            $table->unsignedBigInteger('id_teacher')->nullable();
            $table->enum('exam_type', ['Tugas', 'UTS', 'UAS', 'Kuis']);
            $table->decimal('score', 5, 2);
            $table->text('note')->nullable();

            $table->foreign('id_user')->references('id')->on('tbl_users');
            $table->foreign('id_student')->references('id')->on('tbl_students');
            $table->foreign('id_subject')->references('id')->on('tbl_subjects');
            $table->foreign('id_class')->references('id')->on('tbl_classes');
            $table->foreign('id_teacher')->references('id')->on('tbl_teachers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_exams');
    }
};