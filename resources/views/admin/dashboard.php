<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f4f4;
        }

        header {
            background: #7b5be6;
            color: #fff;
            padding: 20px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        main {
            padding: 32px;
        }

        .card {
            background: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        a {
            color: #7b5be6;
            text-decoration: none;
        }

        .logout {
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <h1>Панель администратора</h1>
        <a class="logout" href="/?page=logout">Выйти</a>
    </header>

    <main>
        <div class="card">
            <h2>Здравствуйте, <?= htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') ?></h2>
            <p>Вы вошли как администратор.</p>
        </div>

        <div class="card">
            <h2>Разделы</h2>
            <ul>
                <li>Управление кинозалами</li>
                <li>Управление фильмами</li>
                <li>Управление сеансами</li>
                <li>Просмотр забронированных билетов</li>
            </ul>
        </div>

        <p><a href="/">Вернуться на сайт</a></p>
    </main>
</body>
</html>



