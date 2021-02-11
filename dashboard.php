<?php

if (!$_COOKIE['auth']) {
    header('Location: index.php');
}

try {
    $connection = new PDO("mysql:host=ID211210_yellowwit.db.webhosting.be;dbname=ID211210_yellowwit", "ID211210_yellowwit", "dbyellowwit1");
} catch (Exception $exception) {
    echo $exception->getMessage();
}

$selectStatement = $connection->prepare("SELECT * FROM users WHERE session_id LIKE :session_id");
$selectStatement->bindParam('session_id', $_COOKIE['auth']);
$selectStatement->setFetchMode(PDO::FETCH_ASSOC);
$selectStatement->execute();
$userInfo = $selectStatement->fetchAll();

foreach ($userInfo as $info) {
    $username = $info['username'];
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>logged in</title>
</head>
<body>
<h1>you are logged in <?= $username ?></h1>
</body>
</html>
