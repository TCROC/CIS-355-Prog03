<?php
session_start();
require "database.php";

if ($_GET)
    $errorMessage = $_GET["errorMessage"];
else
    $errorMessage='';

if ($_POST){
    $success = false;
    $username = $_POST['username'];
    $password = MD5 ($_POST['password']);
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM customers WHERE email='$username' AND password_hash ='$password' LIMIT 1";
    $q = $pdo -> prepare($sql);
    $q -> execute(array());
    $data = $q->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        $_SESSION["username"] = $username;

        header("Location: success.php");
    } else
        header("Location: login.php?errorMessage=Invalid");
}
?>

<h1>Log In</h1>
<form class="form-horizontal" action="login.php" method="post">
    <p style="color: red;"><?php echo($errorMessage); ?></p>
    <input name="username" type="text" placeholder="me@email.com" required>
    <input name="password" type="password" placeholder="password" required>
    <button type="submit" class="btn btn-success">Sign In</button>
    <a href="logout.php">Sign Out</a>
</form>