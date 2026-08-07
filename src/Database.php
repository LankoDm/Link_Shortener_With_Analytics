<?php

function connectDB()
{
    $config = require __DIR__ . "/../config/database.php";

    $dsn = "mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'] . ";charset=utf8mb4";

    try {
        $dbh = new PDO($dsn, $config['user'], $config['password']);

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh;
    } catch (PDOException $e) {
        die("Помилка: " . $e->getMessage());
    }
}