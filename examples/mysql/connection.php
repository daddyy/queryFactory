<?php

$dsn = "mysql://test:test@localhost/test";
$dsn = (parse_url($dsn));
try {
    $connectionString = $dsn['scheme'] . ':host=' . $dsn['host'] . ';dbname=' . ltrim($dsn['path'], '/');
    $connection = new PDO(
        $connectionString,
        $dsn['user'],
        $dsn['pass']
    );
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
