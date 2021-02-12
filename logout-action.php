<?php

// Set up connection with database
try {
    $connection = new PDO("mysql:host=ID211210_yellowwit.db.webhosting.be;dbname=ID211210_yellowwit", "ID211210_yellowwit", "dbyellowwit1");
} catch (Exception $exception) {
    echo $exception->getMessage();
}

// Put the current cookie in a variable
$sessionId = $_COOKIE['auth'];

// Update the session_id of the current logged in user to nothing
$updateStatement = $connection->prepare("UPDATE users SET session_id = '' WHERE session_id = :session_id ");
$updateStatement->bindParam('session_id', $sessionId);
$updateStatement->execute();
// Delete the current browser cookie by setting a cookie in the past
setcookie('auth', '', time() - 3600);
// Redirect the user to the index page without a session_id and without a cookie
header('Location: index.php');
