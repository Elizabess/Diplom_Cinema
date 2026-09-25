<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PDO;

$page = $_GET['page'] ?? 'home';

function renderLayoutStart(string $title): void
{
    echo '<!doctype html>';
    echo '<html lang="ru">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title>';
    echo '</head>';
    echo '<body>';
}

function renderLayoutEnd(): void
{
    echo '<hr>';
    echo '<nav>';
    echo '<a href="/">Главная</a> | ';
    echo '<a href="/?page=health">Проверка приложения</a> | ';
    echo '<a href="/?page=tables">Таблицы</a>';
    echo '</nav>';
    echo '</body>';
    echo '</html>';
}

switch ($page) {
    case 'health':
        renderLayoutStart('Проверка приложения');

        echo '<h1>Приложение работает</h1>';
        echo '<p>PHP успешно выполняет код.</p>';

        renderLayoutEnd();
        break;

    case 'tables':
        renderLayoutStart('Таблицы базы данных');

        try {
            $pdo = \App\Database\Connection::get();

            $statement = $pdo->query('SHOW TABLES');
            $tables = $statement->fetchAll(PDO::FETCH_COLUMN);

            echo '<h1>Таблицы базы данных</h1>';
            echo '<p>Найдено таблиц: ' . count($tables) . '</p>';
            echo '<ul>';

            foreach ($tables as $table) {
                echo '<li>' . htmlspecialchars(
                    (string) $table,
                    ENT_QUOTES,
                    'UTF-8'
                ) . '</li>';
            }

            echo '</ul>';
        } catch (Throwable $exception) {
            http_response_code(500);

            echo '<h1>Ошибка подключения к базе данных</h1>';
            echo '<pre>' . htmlspecialchars(
                $exception->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            ) . '</pre>';
        }

        renderLayoutEnd();
        break;

    case 'home':
    default:
        renderLayoutStart('Кинотеатр');

        echo '<h1>Система бронирования билетов в кинотеатр</h1>';
        echo '<p>Главная страница проекта работает.</p>';

        renderLayoutEnd();
        break;
}
