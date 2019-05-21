<?php
include_once 'db.php';
require_once 'functions.php';

if (isUserLoggedIn()) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = new StdClass;

    $full_name      = isset($_POST['full_name']) ? $_POST['full_name'] : null;
    $email          = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : null;
    $passwd         = isset($_POST['passwd']) ? $_POST['passwd'] : null;
    $passwd_conf    = isset($_POST['passwd_conf']) ? $_POST['passwd_conf'] : null;
    $agree          = isset($_POST['agree']) ? $_POST['agree'] : null;

    $sql = 'SELECT * FROM userAccount WHERE email = :email';
    $handle = $db->prepare($sql);
    $handle->bindValue(':email', $email);
    $handle->execute();
    $user = $handle->fetch(PDO::FETCH_ASSOC);

    // EMAIL VALIDATION
    if (!$email) {
        $error->email = 'Please Specify an Email id';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error->email = 'Please Specify a valid Email id';
    } else if (!isset($user) && empty($user)){
        $error->email = "Sorry, $email is not recognized as an active username";
    }

    // PASSWORD VALIDATION
    if (!$passwd) {
        $error->passwd = 'Please Specify a Password';
    } else if (!password_verify($passwd, $user['password_hash'])) {
        $error->email = 'You have entered an invalid email or password';
    }

    if (empty((array) $error)) {
        session_start();

        $_SESSION['user'] = $user['id'];

        header('Location: index.php');
    }
}


function validInputClass($e) {
    global $error;

    if (isset($error)) {
        return isset($error->{$e}) ? 'is-invalid' : '';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container">
<div class="row align-items-center">
<div class="col-4"></div>
<div class="col-4">
<h1 class="mt-5 pt-5">Please sign in</h1>
<form method="POST">
  <div class="form-group">
    <label for="email">Email address</label>
    <input required type="email" class="<?= validInputClass('email') ?> form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
    <?php if (isset($error->email)) { 
        echo "<div class='invalid-feedback'>$error->email</div>";
    } ?>
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="passwd">Password</label>
    <input required type="password" class="<?= validInputClass('passwd') ?> form-control" id="passwd" name="passwd" placeholder="Password">
    <?php if (isset($error->passwd)) { 
        echo "<div class='invalid-feedback'>$error->passwd</div>";
    } ?>
  </div>
  <button type="submit" class="btn btn-primary">Login</button>
    <div>
    <small>
    Not a member yet?
    <a class="" href="register.php">
    Sign up
    </a>
</small>
    </div>
</form>
</div>
<div class="col-4"></div>
</div>

</div>

<script src="js/main.js"></script>
</body>
</html>