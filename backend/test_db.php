<?php


// Remplace par tes vraies infos OVH
$host = 'https://phpmyadmin-gra.hosting.ovh.net/sanwiidatadmdb74.mysql.db';
$db = 'sanwiidatadmdb74';
$user = 'sanwiidatadmdb74';
$pass = 'L38uuE2G7IpNBAQ';
$port = "3306";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    echo "✅ Connexion réussie à la base de données OVH !";
} catch (\PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
