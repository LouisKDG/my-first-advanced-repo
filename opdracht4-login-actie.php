<?php

try {
    $connection = new PDO("mysql:host=ID211210_dashboard.db.webhosting.be;dbname=ID211210_dashboard", "ID211210_dashboard", "dbdashboard1");
} catch (Exception $exception) {
    echo $exception->getMessage();
}

// Get username from POST data
$username = $_POST['username'];

// Select user from database
$selectStatement = $connection->prepare("SELECT * FROM users WHERE username LIKE :username");
$selectStatement->bindParam('username', $username);
$selectStatement->setFetchMode(PDO::FETCH_ASSOC);
$selectStatement->execute();
$userInfo = $selectStatement->fetchAll();

// Get password from userInfo
foreach ($userInfo as $info) {
    $password = $info['password'];
}

// Compare password from input with database
if (password_verify($_POST['password'], $password)) {
    $sessionId = uniqid();
    $insertStatement = $connection->prepare("UPDATE users SET session_id = :session_id WHERE username = :username");
    $insertStatement->bindParam('session_id', $sessionId);
    $insertStatement->bindParam('username', $username);
    $insertStatement->execute();
    setcookie('auth', $sessionId, time() + 3600);
    header('Location: opdracht4-dashboard.php');
} else {
    header('Location: index.php');
}

?>
