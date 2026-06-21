<?php

use Src\Console\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $pdo = $this->db->getConnection();

        $pdo->exec("DELETE FROM users");

        $hash = password_hash('password', PASSWORD_DEFAULT);

        $users = [
            ['Alice',   'Johnson',  'alice@fuse.io',   '555-0101', 'female', 'admin',  'active'],
            ['Marcus',  'Reed',     'marcus@fuse.io',  '555-0102', 'male',   'editor', 'active'],
            ['Jane',    'Davis',    'jane@fuse.io',    '555-0103', 'female', 'viewer', 'inactive'],
            ['Sam',     'Lee',      'sam@fuse.io',     '555-0104', 'male',   'editor', 'active'],
            ['Amy',     'Brown',    'amy@fuse.io',     '555-0105', 'female', 'viewer', 'pending'],
            ['Kevin',   'Wilson',   'kevin@fuse.io',   '555-0106', 'male',   'admin',  'active'],
            ['Tina',    'Park',     'tina@fuse.io',    '555-0107', 'female', 'editor', 'active'],
            ['Noah',    'Martinez', 'noah@fuse.io',    '555-0108', 'male',   'viewer', 'inactive'],
            ['Grace',   'Harris',   'grace@fuse.io',   '555-0109', 'female', 'editor', 'active'],
            ['Ryan',    'Kim',      'ryan@fuse.io',    '555-0110', 'male',   'viewer', 'active'],
            ['Lena',    'Scott',    'lena@fuse.io',    '555-0111', 'female', 'viewer', 'pending'],
            ['Derek',   'Adams',    'derek@fuse.io',   '555-0112', 'male',   'editor', 'active'],
            ['Sofia',   'Torres',   'sofia@fuse.io',   '555-0113', 'female', 'admin',  'active'],
            ['Ethan',   'Clark',    'ethan@fuse.io',   '555-0114', 'male',   'viewer', 'inactive'],
            ['Mia',     'White',    'mia@fuse.io',     '555-0115', 'female', 'editor', 'active'],
            ['Lucas',   'Hall',     'lucas@fuse.io',   '555-0116', 'male',   'viewer', 'active'],
            ['Olivia',  'Young',    'olivia@fuse.io',  '555-0117', 'female', 'editor', 'pending'],
        ];

        $stmt = $pdo->prepare(
            "INSERT INTO users (first_name, last_name, email, phone, gender, role, status, password)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        foreach ($users as $u) {
            $stmt->execute([...$u, $hash]);
        }
    }
}
