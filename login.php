<?php
require "database.php";
if ($_POST){
    $success = false;
    $username = $_POST['username'];
    $password = $_POST['password'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM customers WHERE email='$username' AND password_hash ='temp'";
    $q = $pdo -> prepare($sql);
    $q -> execute(array());
    $data = $q->fetch(PDO::FETCH_ASSOC);
    print_r($data); exit();
    if ($success) {
        $_SESSION["username"] = $username;

        header("Location: success.php?id=$sessionid");
    } else
        header("Location: login.php");
}
?>

<h1>Log In</h1>
<form class="form-horizontal" action="login.php" method="post">
    <input name="username" type="text" placeholder="me@email.com" required>
    <input name="password" type="password" placeholder="password" required>
    <button type="submit" class="btn btn-success">Sign In</button>
</form>