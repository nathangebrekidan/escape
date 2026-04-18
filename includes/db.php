<?php

try {
    $host = 'localhost';
    $db = 'delta';
    $user = 'root';
    $password = '';
    
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    error_log('Database connection error: ' . $e->getMessage());
    exit();
}

?>
