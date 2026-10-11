<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
session_start();

header('Content-Type: text/html; charset=utf-8');

$page = $_GET['page'] ?? 'home';

function renderLayoutStart(string $title): void
{
    echo '<!doctype html>';
    echo '<html lang="ru">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<link rel="stylesheet" href="/assets/client-css/normalize.css">';
    echo '<link rel="stylesheet" href="/assets/client-css/styles.css">';
    echo '<title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title>';
    echo '</head>';
    echo '<body>';
}

function renderLayoutEnd(): void
{
    echo '<hr>';
    echo '<nav>';
    echo '<a href="/">Главная</a> | ';
    echo '<a href="/?page=hall">Выбор мест</a> | ';
    echo '<a href="/?page=payment">Подтверждение бронирования</a> | ';
    echo '<a href="/?page=ticket">Билет</a> | ';
    echo '<a href="/?page=health">Проверка приложения</a> | ';
    echo '<a href="/?page=tables">Таблицы</a>';
    echo '</nav>';
    echo '</body>';
    echo '</html>';
}

function renderClientView(string $filename): void
{
    $allowedViews = [
        'index.html',
        'hall.html',
        'payment.html',
        'ticket.html',
    ];

    if (!in_array($filename, $allowedViews, true)) {
        http_response_code(404);

        echo '<h1>Страница не найдена</h1>';
        return;
    }

    $path = __DIR__ . '/../resources/views/layouts/client/' . $filename;

    if (!is_file($path)) {
        http_response_code(500);

        echo '<h1>Файл шаблона не найден</h1>';
        echo '<p>Не найден файл: ' . htmlspecialchars(
            $filename,
            ENT_QUOTES,
            'UTF-8'
        ) . '</p>';
        return;
    }

    require $path;
}

function requireAdmin(): array
{
    if (empty($_SESSION['admin_user_id'])) {
	header('Location: /?page=login');
	exit;
    }

    return [
	'id' => (int) $_SESSION['admin_user_id'],
	'name' => (string) ($_SESSION['admin_user_name'] ?? ''),
    ];
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

    case 'hall':
        renderClientView('hall.html');
        break;

    case 'payment':
        renderClientView('payment.html');
        break;

    case 'ticket':
        renderClientView('ticket.html');
        break;

    case 'login':
        $error = '';
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                $error = 'Введите email и пароль.';
            } else {
                try {
                    $pdo = \App\Database\Connection::get();

                    $statement = $pdo->prepare(
                        'SELECT id, email, password_hash, role, name
                         FROM users
                         WHERE email = :email
                         LIMIT 1'
                    );

                    $statement->execute(['email' => $email]);
                    $user = $statement->fetch();

                    if (
                        $user === false
                        || $user['role'] !== 'admin'
                        || !password_verify($password, $user['password_hash'])
                    ) {
                        $error = 'Неверный email или пароль.';
                    } else {
                        session_regenerate_id(true);

                        $_SESSION['admin_user_id'] = (int) $user['id'];
                        $_SESSION['admin_user_name'] = (string) $user['name'];

                        header('Location: /?page=admin');
                        exit;
                    }
                } catch (Throwable $exception) {
                    $error = 'Ошибка сервера. Попробуйте позже.';
                }
            }
        }

        require __DIR__ . '/../resources/views/admin/login.php';
        break;

    case 'admin':
        $admin = requireAdmin();

        $adminName = $admin['name'];

        require __DIR__ . '/../resources/views/admin/dashboard.php';
        break;

    case 'logout':
        session_destroy();

        header('Location: /?page=login');
        exit;

    case 'home':
    default:
        renderClientView('index.html');
        break;
}
