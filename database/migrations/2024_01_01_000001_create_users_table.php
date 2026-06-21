<?php

class CreateUsersTable
{
    public function up()
    {
        $pdo = \Src\Database::getInstance()->getConnection();
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id          INT AUTO_INCREMENT PRIMARY KEY,
                first_name  VARCHAR(100) NOT NULL,
                last_name   VARCHAR(100) NOT NULL,
                email       VARCHAR(255) NOT NULL UNIQUE,
                phone       VARCHAR(30)  DEFAULT NULL,
                gender      ENUM('male','female','other') DEFAULT NULL,
                age         TINYINT UNSIGNED DEFAULT NULL,
                address     TEXT DEFAULT NULL,
                password    VARCHAR(255) DEFAULT NULL,
                created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down()
    {
        $pdo = \Src\Database::getInstance()->getConnection();
        $pdo->exec("DROP TABLE IF EXISTS users");
    }
}
