<?php
// csrf.php - защита от CSRF-атак

/**
 * Генерирует новый CSRF-токен
 * Сохраняет в сессии и возвращает
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
    }
    return $_SESSION['csrf_token'];
}

/**
 * Возвращает HTML-поле с токеном
 */
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
}

/**
 * Проверяет CSRF-токен из POST-запроса
 * Возвращает true если токен верный
 */
function validateCsrfToken() {
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

/**
 * Проверяет токен и завершает скрипт при ошибке
 * Не удаляет токен, чтобы можно было отправлять несколько запросов
 */
function checkCsrfToken() {
    if (!validateCsrfToken()) {
        die('Ошибка CSRF: неверный или отсутствующий токен. Обновите страницу и попробуйте снова.');
    }
}