<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role', function (Blueprint $table) {
            $table->integer('id_role')->autoIncrement();
            $table->string('nama_role', 100);
        });

        Schema::create('user', function (Blueprint $table) {
            $table->integer('id_user')->autoIncrement();
            $table->string('nama_lengkap', 150);
            $table->string('nim', 20)->nullable()->unique();
            $table->string('nik', 20)->nullable()->unique();
            $table->string('email', 150)->unique();
            $table->string('password', 255);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('user_role', function (Blueprint $table) {
            $table->integer('id_user_role')->autoIncrement();
            $table->integer('user_id');
            $table->integer('role_id');

            $table->foreign('user_id')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('role_id')->references('id_role')->on('role')->onDelete('cascade');
            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('user');
        Schema::dropIfExists('role');
    }
};
