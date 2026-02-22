<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 13);
            $table->string('cpf', 11)->unique();

            $table->timestamp('last_access')->nullable();
            $table->string('token_recovery_password')->nullable();
            $table->timestamp('last_time_password_changed')->nullable();

            $table->integer('login_attempts')->default(0);
            $table->boolean('blocked')->default(0);

            $table->softDeletes();
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
