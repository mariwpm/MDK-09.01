<?php
session_start();
require_once 'db.php';
require_once 'csrf.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен быть минимум 6 символов';
    } else {
        $pdo = getConnection();
        
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким email уже существует';
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $password]);
            
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            header('Location: index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Создать аккаунт</h1>
        
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <?= csrfField() ?>
            <input type="text" name="name" placeholder="Как вас зовут?" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль (минимум 6 символов)" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </div>
</body>
</html>