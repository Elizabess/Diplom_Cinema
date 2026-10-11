<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход администратора</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            background: #fff;
            padding: 32px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            width: 320px;
        }

        h1 {
            font-size: 22px;
            margin-top: 0;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #7b5be6;
            color: #fff;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
        }

        .error {
            color: #c62828;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <main class="login-card">
        <h1>Вход администратора</h1>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="/?page=login">
            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                required
                value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >

            <label for="password">Пароль</label>
            <input
                id="password"
                type="password"
                name="password"
                required
            >

            <button type="submit">Войти</button>
        </form>
    </main>
</body>
</html>