<?php
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
    }
    return $_SESSION['csrf_token'];
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
}

function validateCsrfToken() {
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

function checkCsrfToken() {
    if (!validateCsrfToken()) {
        die('Ошибка CSRF: неверный или отсутствующий токен. Обновите страницу и попробуйте снова.');
    }
}