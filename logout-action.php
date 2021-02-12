<?php

// Maak verbinding met database
try {
    $connection = new PDO("mysql:host=ID211210_yellowwit.db.webhosting.be;dbname=ID211210_yellowwit", "ID211210_yellowwit", "dbyellowwit1");
} catch (Exception $exception) {
    echo $exception->getMessage();
}

// Steek de huidige cookie in een variabele
$sessionId = $_COOKIE['auth'];

// Update de session_id van de huidige user naar niets
$updateStatement = $connection->prepare("UPDATE users SET session_id = '' WHERE session_id = :session_id ");
$updateStatement->bindParam('session_id', $sessionId);
$updateStatement->execute();
// Verwijder de huidige cookie door deze in het verleden aan te maken
setcookie('auth', '', time() - 3600);
// Redirect de gebruiker naar de homepage zonder cookie en zonder session_id
header('Location: index.php');
