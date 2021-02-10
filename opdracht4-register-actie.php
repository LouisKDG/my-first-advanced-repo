<?php

try {
    $connection = new PDO("mysql:host=ID211210_dashboard.db.webhosting.be;dbname=ID211210_dashboard", "ID211210_dashboard", "dbdashboard1");
} catch (Exception $exception) {
    echo $exception->getMessage();
}

// Get username from POST data
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Add user from database
$insertStatement = $connection->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
$insertStatement->bindParam('username', $username);
$insertStatement->bindParam('password', $password);
$insertStatement->execute();
header('Location: index.php');

?>

