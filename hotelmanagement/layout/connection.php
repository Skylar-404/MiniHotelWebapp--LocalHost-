<?php
$port = 3306;
$dbname = '[YOUR_DATABASE_NAME]';
$user = '';
$pwd = '';

$pdo = new PDO("mysql:host=localhost;port=$port;dbname=$dbname", $user, $pwd);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
