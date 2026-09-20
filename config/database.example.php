<?php

declare(strict_types=1);

$host = '127.0.0.1';
$port = '3306';
$dbname = 'cinema_booking';
$username = 'cinema_user';
$password = 'CHANGE_ME';

$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

return new PDO($dsn, $username, $password, $options);
