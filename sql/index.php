<?php
require_once 'connect.php';

// create table users
$sql = "CREATE TABLE IF NOT EXISTS users (id INT(11) NOT NULL AUTO_INCREMENT,
                                          username VARCHAR(16) NOT NULL,
                                          age INT(11) NOT NULL,
                                          description TEXT NULL,
                                          PRIMARY KEY (id))";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// create table comments
$sql = "CREATE TABLE IF NOT EXISTS comments (id INT(11) NOT NULL AUTO_INCREMENT,
                                          user_id VARCHAR(16) NOT NULL,
                                          message TEXT NULL,
                                          PRIMARY KEY (id))";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// create table animals
$sql = "CREATE TABLE IF NOT EXISTS animals (id INT(11) NOT NULL AUTO_INCREMENT,
                                          user_id INT(11) NOT NULL,
                                          animal TEXT NULL,
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

// insert message
if (isset($_POST['submit_add_message'])) {
    $user_id = $_POST['user'];
    $message = $_POST['message'];

    if ($user_id && $message) {
        $sql = "INSERT INTO comments (user_id, message) VALUES (:user_id, :message)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':message', $message, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        echo 'Сообщение не может быть пустым.';
    }
}

// get users
$sql = "SELECT id, username, age FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();

// show users
if (isset($_POST['submit_show_users'])) {

    foreach ($users as $user) {
        echo $user['username'].' '.$user['age'].'<br>';
    }
}

// show messages
if (isset($_POST['submit_show_messages'])) {
    $sql = "SELECT c.message, u.username FROM comments AS c LEFT JOIN users AS u ON c.user_id = u.id ORDER BY c.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $messages = $stmt->fetchAll();
    foreach ($messages as $message) {
        echo $message['username'].'<br>'.$message['message'].'<br><br>';
    }
}

// show only NO cat ACROSS SUBQUESTION
$sql = "SELECT user_id, animal FROM animals WHERE animal != :animal AND user_id NOT IN (SELECT user_id FROM animals WHERE animal = :animal)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':animal', 'cat', PDO::PARAM_STR);
$stmt->execute();
$personals = $stmt->fetchAll();

echo 'WITH SUBQUESTION<br>';
foreach ($personals as $person) {
    echo 'person: '.$person['user_id'].' animal: '.$person['animal'].'<br>';
}

echo "<br><br>";
echo 'WITH JOIN<br>';
$sql = "SELECT u.id, a.animal FROM users AS u LEFT JOIN animals AS a ON u.id = a.user_id WHERE a.animal != :animal";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':animal', 'cat', PDO::PARAM_STR);
$stmt->execute();
$personals = $stmt->fetchAll();
foreach ($personals as $person) {
    echo 'person: '.$person['id'].' animal: '.$person['animal'].'<br>';
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

<hr>

<form action="index.php" method="POST">
    <select name="user">
        <?php
            foreach ($users as $user) {
                echo "<option value='".$user['id']."'>".$user['username']."</option>";
            }
        ?>
    </select><br>
    <label for="message">Cообщение:<br> <textarea name="message"></textarea><br>
    <input type="submit" name="submit_add_message" value="Добавить сообщение">
</form>
<br><br>
<form action="index.php" method="POST">
    <input type="submit" name="submit_show_messages" value="Показать сообщения">
</form>