<?php
$dsn = 'mysql:host=localhost;dbname=dict';
$user = 'root';
$pass = '123';
$opt = [
    // if error - throw exception
    PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
    // fetch assoc as default mode
    PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
    // constant connect for saving resources
    PDO::ATTR_PERSISTENT          => true
];
$pdo = new PDO($dsn, $user, $pass, $opt);