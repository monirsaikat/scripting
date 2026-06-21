<?php

use Src\Database\Schema;
use Src\Database\Blueprint;

class CreateUsersTable
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->tinyInteger('age')->unsigned()->nullable();
            $table->text('address')->nullable();
            $table->string('password')->nullable();
            $table->enum('role', ['admin', 'editor', 'viewer'])->default('viewer');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
