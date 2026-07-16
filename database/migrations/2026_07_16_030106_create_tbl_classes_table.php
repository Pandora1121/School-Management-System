<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_classes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('creation_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->unsignedBigInteger('create_id')->nullable();
            $table->unsignedBigInteger('update_id')->nullable();
            $table->tinyInteger('archived')->default(0);
            $table->unsignedBigInteger('id_user')->nullable();

            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedBigInteger('id_major');
            $table->text('description')->nullable();

            $table->foreign('id_user')->references('id')->on('tbl_users');
            $table->foreign('id_major')->references('id')->on('tbl_majors');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_classes');
    }
};