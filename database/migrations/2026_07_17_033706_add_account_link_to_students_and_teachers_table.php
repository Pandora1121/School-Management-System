<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_students', function (Blueprint $table) {
            $table->unsignedBigInteger('id_account')->nullable()->after('id_user');
            $table->foreign('id_account')->references('id')->on('tbl_users');
        });

        Schema::table('tbl_teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('id_account')->nullable()->after('id_user');
            $table->foreign('id_account')->references('id')->on('tbl_users');
        });

        Schema::table('tbl_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_wali_kelas')->nullable()->after('id_major');
            $table->foreign('id_wali_kelas')->references('id')->on('tbl_teachers');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_students', function (Blueprint $table) {
            $table->dropForeign(['id_account']);
            $table->dropColumn('id_account');
        });
        Schema::table('tbl_teachers', function (Blueprint $table) {
            $table->dropForeign(['id_account']);
            $table->dropColumn('id_account');
        });
        Schema::table('tbl_classes', function (Blueprint $table) {
            $table->dropForeign(['id_wali_kelas']);
            $table->dropColumn('id_wali_kelas');
        });
    }
};