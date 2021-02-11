<?php

// Set up connection with database
try {
    $connection = new PDO("mysql:host=ID211210_yellowwit.db.webhosting.be;dbname=ID211210_yellowwit", "ID211210_yellowwit", "dbyellowwit1");
} catch (Exception $exception) {
    echo $exception->getMessage();
}

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// First check if username and password are set, if they are set count if the username already exists in database and put count in variable
if (isset($username) && isset($password)) {
    $checkStatement = $connection->prepare("SELECT COUNT(*) FROM users WHERE username LIKE :username");
    $checkStatement->bindParam('username', $username);
    $checkStatement->execute();
    $count = $checkStatement->fetchColumn();
    // In the same if-statement, check if the register field are empty, if one or both are empty, return a alert
    if (empty($username) || empty($password)) {
        echo "<script>alert('Vul alle velden in')</script>";
        // If they are both filled in, check if the count variable you made earlier is higher than 0, if it is higher than 0 return a alert
    } elseif ($count > 0) {
        echo "<script>alert('Gebruikersnaam reeds in gebruik')</script>";
        // If everything is fine go on with the register process and insert the username and hashed password in your database and return a alert
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $insertStatement = $connection->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $insertStatement->bindParam('username', $username);
        $insertStatement->bindParam('password', $password_hashed);
        $insertStatement->execute();
        echo "<script>alert('Bedankt voor uw registratie')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yellowwit - Log in/Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<form action="login-action.php" method="post" class="form">
    <p class="question">Already registered?</p>
    <h1>Login</h1>
    <p>
        <label>username</label>
        <input type="text" name="username">
    </p>
    <p>
        <label>password</label>
        <input type="password" name="password">
    </p>
    <button class="button">Log in</button>
</form>

<form action="index.php" method="post" class="form">
    <p class="question">Not yet registered?</p>
    <h1>Register</h1>
    <p>
        <label>username</label>
        <input type="text" name="username">
    </p>
    <p>
        <label>password</label>
        <input type="password" name="password">
    </p>
    <button class="button">Register</button>
</form>
</body>
</html>
