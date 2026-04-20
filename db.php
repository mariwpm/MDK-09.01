<?php
function getConnection(): PDO {
    $host = '127.0.0.1';
    $port = '3306';
    $dbname = 'notes_app';
    $user = 'root';
    $password = 'mariwpm050628!';  
    
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    try {
        return new PDO($dsn, $user, $password, $options);
    } catch (PDOException $e) {
        die('Ошибка подключения к базе данных: ' . $e->getMessage());
    }
}