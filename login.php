<?php
session_start();
require_once 'db.php';
require_once 'csrf.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND password_hash = ?');
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Вход в заметки</h1>
        
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <?= csrfField() ?>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
        
        <p>Нет аккаунта? <a href="register.php">Создать</a></p>
    </div>
</body>
</html>