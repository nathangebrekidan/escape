<?php
$server='localhost';
$username='root';
$password='';
$db='delta';
try {
    $pdo = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $q = $pdo->query('SELECT id,riddle,answer,roomId FROM riddles WHERE roomId=3 ORDER BY id ASC');
    $rows = $q->fetchAll(PDO::FETCH_ASSOC);
    echo 'count=' . count($rows) . "\n";
    foreach ($rows as $r) {
        echo $r['id'] . ':' . str_replace("\n", ' ', $r['riddle']) . ' -> ' . $r['answer'] . ' roomId=' . $r['roomId'] . "\n";
    }
} catch (PDOException $e) {
    echo 'ERR:' . $e->getMessage();
}
