<?php

use Src\Console\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $pdo = $this->db->getConnection();

        $pdo->exec("DELETE FROM admins");

        $pdo->prepare("INSERT INTO admins (name, email, password, is_active) VALUES (?, ?, ?, 1)")
            ->execute(['Super Admin', 'admin@example.com', password_hash('admin123', PASSWORD_DEFAULT)]);
    }
}
