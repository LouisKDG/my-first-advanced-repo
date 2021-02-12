<?php

// Check if cookie exists, if it does not exist, redirect to index page
if (!$_COOKIE['auth']) {
    header('Location: index.php');
}

$id = $_GET['blog'];

// Set up connection with database
try {
    $connection = new PDO("mysql:host=ID211210_yellowwit.db.webhosting.be;dbname=ID211210_yellowwit", "ID211210_yellowwit", "dbyellowwit1");
} catch (Exception $exception) {
    echo $exception->getMessage();
}

// Select all information from the logged in user
$selectStatement = $connection->prepare("SELECT * FROM users WHERE session_id LIKE :session_id");
$selectStatement->bindParam('session_id', $_COOKIE['auth']);
$selectStatement->setFetchMode(PDO::FETCH_ASSOC);
$selectStatement->execute();
$userInfo = $selectStatement->fetchAll();

// Put the username from logged in person in a variable so we can use it
foreach ($userInfo as $info) {
    $username = $info['username'];
}

// Select information from clicked blogpost by matching the id's
$selectStatementPosts = $connection->prepare("SELECT * FROM posts WHERE id = :id");
$selectStatementPosts->bindParam('id', $id);
$selectStatementPosts->execute();
$postInfo = $selectStatementPosts->fetchAll();

// Select and translate the right author by inner joining on post id
$translateStatement = $connection->prepare("SELECT username FROM users INNER JOIN posts ON users.id=posts.user_id WHERE posts.id LIKE :id ");
$translateStatement->bindParam('id', $id);
$translateStatement->execute();
$author = $translateStatement->fetchColumn();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<body>
<section class="top">
    <div>
        <p>Welcome <b><?= $username ?></b></p>
    </div>
    <div>
        <form action="logout-action.php" method="post" class="test">
            <button class="logout">Logout</button>
        </form>
    </div>
</section>

<section class="blog">
    <br>
    <br>
    <?php foreach ($postInfo as $post): ?>
        <div class="post">
            <p class="title"> title: <b><?= $post['title']; ?></a></b></p>
            <p><?= $post['body'] ?></p>
            <p class="author">author: <b><?= $author?></b></p>
            <?php if ($username == $author): ?>
            <a href="blogpost-edit.php?blog=<?= $post['id']?>">edit post</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>
</body>
</html>
