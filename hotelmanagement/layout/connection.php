<?php
$port = 3306;
$dbname = 'phphotel';
$user = 'phpboss';
$pwd = 'bbu7';

$pdo = new PDO("mysql:host=localhost;port=$port;dbname=$dbname", $user, $pwd);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
