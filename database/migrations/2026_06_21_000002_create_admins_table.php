<?php

use Src\Database\Schema;
use Src\Database\Blueprint;
use Src\Database;

class CreateAdminsTable
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });

        Database::getInstance()->insert('admins', [
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'is_active' => 1,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
}
