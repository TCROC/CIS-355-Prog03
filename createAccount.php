<?php
session_start();
require "database.php";

$emailVerificationUrl = "http://localhost:63342/Prog03/verifyEmail.php";

if ($_GET)
    $errorMessage = $_GET["errorMessage"];
else
    $errorMessage='';

if ($_POST){
    // Create an account with the data given from the post.
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = MD5 ($_POST['password']);
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Add the data to the database.
    $sql = "INSERT INTO customers (name,email,mobile,password_hash) values(?, ?, ?, ?)";
    $q = $pdo -> prepare($sql);
    $q -> execute(array($name, $email, $mobile, $password));

    // Now try to query that username / password combination to make sure the account was created successfully.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM customers WHERE email = ? AND password_hash = ? LIMIT 1";
    $q = $pdo->prepare($sql);
    $q->execute(array($email,$password));
    $data = $q->fetch(PDO::FETCH_ASSOC);

    // If we got data back, the account was created successfully. Go to customer.php.
    if ($data) {
        $_SESSION["username"] = $email;

        $to      = $email; // Send email to our user
        $subject = 'Email Verification';
        $message = '
 
        Thanks for joining!
         
        Please click this link to verify your email:
        '.$emailVerificationUrl.'?email='.$email.'&password='.$password.'';

        $headers = 'From:noreply@customers.com' . "\r\n"; // Set from header
        mail($to, $subject, $message, $headers); // Send our email

        header("Location: customer.php");
    } else // Otherwise, try creating an account in again.
        header("Location: createAccount.php?errorMessage=Something went wrong. Please try again.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\"
            integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\"
            crossorigin=\"anonymous\"></script>
    <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
    <style>label {width: 5em;}</style>
</head>

<div class="container">
    <h1>Join</h1>
    <form method="post">
        Name: <br><input name="name" type="text" placeholder="name" required><br>
        Email: <br><input name="email" type="text" placeholder="me@email.com" required><br>
        Mobile (123-456-7890): <br><input name="mobile" type="tel" placeholder="123-456-7890" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required><br>
        Password: <br><input name="password" type="password" placeholder="password" required><br>
        <button type="submit" class="btn btn-success">Join</button>
        <?php
        // Display's an error message if there is one.
        if ($errorMessage) {
            echo "<p class=\"alert alert-danger\" role=\"alert\">$errorMessage</p>";
        }
        ?>
    </form>
</div>
</html>
