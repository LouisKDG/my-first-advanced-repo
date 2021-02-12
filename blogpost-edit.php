<?php

// Set up connection with database
try {
    $connection = new PDO("mysql:host=ID211210_yellowwit.db.webhosting.be;dbname=ID211210_yellowwit", "ID211210_yellowwit", "dbyellowwit1");
} catch (Exception $exception) {
    echo $exception->getMessage();
}

$id = $_GET['blog'];

// Check if user can access this page by comparing it's session id with cookie
$compareStatement = $connection->prepare("SELECT session_id FROM users INNER JOIN posts ON users.id=posts.user_id WHERE posts.id LIKE :id ");
$compareStatement->bindParam('id', $id);
$compareStatement->setFetchMode(PDO::FETCH_ASSOC);
$compareStatement->execute();
$session = $compareStatement->fetchColumn();

// Check if cookie exists, if it does not exist, redirect to index page
if ($_COOKIE['auth'] !== $session) {
    header('Location: index.php');
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
    $session_id = $info['session_id'];
}

// Select information from clicked blogpost by matching the id's
$selectStatementPosts = $connection->prepare("SELECT * FROM posts WHERE id = :id");
$selectStatementPosts->bindParam('id', $id);
$selectStatementPosts->execute();
$postInfo = $selectStatementPosts->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $insertStatement = $connection->prepare("UPDATE posts SET title = :title, body = :body WHERE `posts`.`id` = :id ");
    $insertStatement->bindParam('title', $_POST['newTitle']);
    $insertStatement->bindParam('body', $_POST['newBody']);
    $insertStatement->bindParam('id', $id);
    $insertStatement->execute();

    header('Location: dashboard.php');
}

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
        <section class="blog">
            <h1>Edit blog</h1>
            <form action="blogpost-edit.php?blog=<?= $post['id']?>" method="post">
                <p>title</p>
                <input type="text" name="newTitle" value="<?= $post['title']; ?>">
                <br>
                <p>blog</p>
                <textarea name="newBody" rows="4" cols="50"><?= $post['body'] ?></textarea>
                <br>
                <button class="blogpost">Edit</button>
            </form>
        </section>
    <?php endforeach; ?>
</section>
</body>
</html>
