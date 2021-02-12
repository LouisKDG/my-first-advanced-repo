<?php

// Check if cookie exists, if it does not exist, redirect to index page
if (!$_COOKIE['auth']) {
    header('Location: index.php');
}

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
    $userId = $info['id'];
}

// Select all information from blogposts so we can use it
$selectStatementPosts = $connection->prepare("SELECT * FROM posts");
$selectStatementPosts->setFetchMode(PDO::FETCH_ASSOC);
$selectStatementPosts->execute();
$postInfo = $selectStatementPosts->fetchAll();

// Translate the foreign key from posts to author name in chronological order by inner joining and ordering by time
$translateStatement = $connection->prepare("SELECT username FROM users INNER JOIN posts ON users.id=posts.user_id ORDER BY created_at");
$translateStatement->execute();
$translations = $translateStatement->fetchAll();

// Make an array for the authors
$authors = [];
// Start array at -1, because at 0 it won't work, I don't know why???
$i = -1;

// Push all the authors in the author array
foreach ($translations as $translation) {
    $author = $translation['username'];
    array_push($authors, $author);
}

// First check if username and password are set, if they are set count if the username already exists in database and put count in variable
if (isset($_POST['title']) && isset($_POST['body'])) {
    // In the same if-statement, check if the register field are empty, if one or both are empty, return a alert
    if (empty($_POST['title']) || empty($_POST['body'])) {
        echo "<script>alert('Vul alle velden in')</script>";
        // If they are both filled in, check if the count variable you made earlier is higher than 0, if it is higher than 0 return a alert
    } else {
        $insertStatement = $connection->prepare("INSERT INTO posts (title, body, user_id) VALUES(:title, :body, :id)");
        $insertStatement->bindParam('title', $_POST['title']);
        $insertStatement->bindParam('body', $_POST['body']);
        $insertStatement->bindParam('id', $userId);
        $insertStatement->execute();
        echo "<script>alert('Bedankt voor uw post')</script>";
        die();
    }
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
    <h1>Schrijf blogpost</h1>
    <form action="dashboard.php" method="post">
        <p>title  <input type="text" name="title"></p>
        <br>
        <p>post  <textarea name="body" rows="4" cols="50"></textarea></p>
        <br>
        <button class="blogpost">Post</button>
    </form>
</section>

<section class="blog">
<h1>Blog overzicht</h1>
    <?php foreach ($postInfo as $post): ?>
        <?php $i++ ?>
    <div class="post">
        <p class="title"> title: <b><a href="blogpost-details.php?blog=<?= $post['id']?>"><?= $post['title']; ?></a></b></p>
        <p class="author">author: <b><?= $authors[$i]?></b></p>
    </div>
    <?php endforeach; ?>
</section>
</body>
</html>
