<?php
require_once 'connect.php';

// create table
$sql = "CREATE TABLE IF NOT EXISTS users (id INT(11) NOT NULL AUTO_INCREMENT,
                                          username VARCHAR(16) NOT NULL,
                                          age INT(11) NOT NULL,
                                          description TEXT NULL,
                                          PRIMARY KEY (id))";
$stmt = $pdo->prepare($sql);
$stmt->execute();


// insert users
if (isset($_POST['submit_add_user'])) {
    $user = trim($_POST['username']);
    $age = trim($_POST['age']);
    if ($user && $age) {
        $sql = "INSERT INTO users (username, age, description) VALUES (:username, :age, 'default')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $user, PDO::PARAM_STR);
        $stmt->bindValue(':age', $age, PDO::PARAM_INT);
        $stmt->execute();
        echo 'User saved. His id is ' . $pdo->lastInsertId() . '<br>';
    } else {
        echo 'Имя пользователя и возраст не могут быть пустыми';
    }
}

// show users
if (isset($_POST['submit_show_users'])) {

    $sql = "SELECT username, age FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
    foreach ($users as $user) {
        echo $user['username'].' '.$user['age'].'<br>';
    }
}

$pdo = null;
?>

<form action="index.php" method="POST">
    <label for="username">Имя: <input type="text" name="username"></label><br>
    <label for="age">Возраст: <input type="text" name="age"></label><br>
    <input type="submit" name="submit_add_user" value="Добавить пользователя">
</form>

<form action="index.php" method="POST">
    <input type="submit" name="submit_show_users" value="Показать пользователей">
</form>