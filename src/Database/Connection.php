<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

final class Connection
{
    public static function get(): PDO
    {
        return require __DIR__ . '/../../config/database.php';
    }
}
