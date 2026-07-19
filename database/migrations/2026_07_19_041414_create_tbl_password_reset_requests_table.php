<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_password_reset_requests', function (Blueprint $table) {
            $table->id();
            $table->dateTime('creation_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->unsignedBigInteger('create_id')->nullable();
            $table->unsignedBigInteger('update_id')->nullable();
            $table->tinyInteger('archived')->default(0);

            $table->unsignedBigInteger('id_user');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=selesai');

            $table->foreign('id_user')->references('id')->on('tbl_users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_password_reset_requests');
    }
};