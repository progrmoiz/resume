<?php
include_once 'db.php';
require_once 'functions.php';

if (isUserLoggedIn()) {
    header('Location: index.php');
}

$full_name = $email = $passwd = $passwd_conf = $agree = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = new StdClass;

    $full_name      = isset($_POST['full_name']) ? $_POST['full_name'] : null;
    $email          = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : null;
    $passwd         = isset($_POST['passwd']) ? $_POST['passwd'] : null;
    $passwd_conf    = isset($_POST['passwd_conf']) ? $_POST['passwd_conf'] : null;
    $agree          = isset($_POST['agree']) ? $_POST['agree'] : null;

    // FULL NAME VALIDATION
    if (!$full_name) {
        $error->full_name = 'Please Specify an Full Name';
    }

    // EMAIL VALIDATION
    if (!$email) {
        $error->email = 'Please Specify an Email id';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error->email = 'Please Specify a valid Email id';
    }

    $sql = 'SELECT * FROM userAccount WHERE email = :email';
    $handle = $db->prepare($sql);
    $handle->bindValue(':email', $email);
    $handle->execute();
    $user = $handle->fetch(PDO::FETCH_ASSOC);

    if (isset($user) && !empty($user)){
        $error->email = 'Please use a different Email id';
    }

    // PASSWORD VALIDATION
    if (!$passwd) {
        $error->passwd = 'Please Specify a Password';
    }

    // PASSWORD CONFIRM VALIDATION
    if (!$passwd_conf) {
        $error->passwd_conf = 'Please Specify a Password Confirmation';
    } else if (strcmp($passwd, $passwd_conf) != 0) {
        $error->passwd_conf = 'Password confirmation must match Password';
    }

    // TERM OF SERVICE VALIDATION
    if (!$agree) {
        $error->agree = 'You must agree before submitting.';
    }

    if (empty((array) $error)) {
        session_start();

        $pass_hash = password_hash($passwd, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO `userAccount`(`name`, `email`, `password_hash`) VALUES (:name,:email,:hash)';
        $handle = $db->prepare($sql);
        $handle->bindValue(':name', $full_name);
        $handle->bindValue(':email', $email);
        $handle->bindValue(':hash', $pass_hash);

        try {
            $db->beginTransaction();
            $handle->execute();
            $_SESSION['user'] = $db->lastInsertId();
            $db->commit();
        } catch(PDOExecption $e) {
            $dbh->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        header('Location: index.php');
    }
}

function validInputClass($e) {
    global $error;

    if (isset($error)) {
        return isset($error->{$e}) ? 'is-invalid' : 'is-valid';
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
<div class="row">
<div class="col-4"></div>
<div class="col-4">
<h1 class="mt-5 pt-5">Sign Up</h1>
<form method="POST">
   <div class="form-group">
    <label for="full_name">Full Name</label>
    <input required type="text" class="<?= validInputClass('full_name') ?> form-control" value="<?= $full_name ?>" name="full_name" id="full_name" placeholder="Full name">
    <?php if (isset($error->full_name)) { 
        echo "<div class='invalid-feedback'>$error->full_name</div>";
    } else {
        echo '<div class="valid-feedback">Looks good!</div>';
    } ?>
  </div>
  <div class="form-group">
    <label for="email">Email address</label>
    <input required type="email" class="<?= validInputClass('email') ?> form-control" value="<?= $email ?>" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
    <?php if (isset($error->email)) { 
        echo "<div class='invalid-feedback'>$error->email</div>";
    } else {
        echo '<div class="valid-feedback">Looks good!</div>';
    } ?>
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="passwd">Password</label>
    <input required type="password" class="<?= validInputClass('passwd') ?> form-control" value="<?= $passwd ?>" id="passwd" name="passwd" placeholder="Password">
    <?php if (isset($error->passwd)) { 
        echo "<div class='invalid-feedback'>$error->passwd</div>";
    } else {
        echo '<div class="valid-feedback">Looks good!</div>';
    } ?>
  </div>
  <div class="form-group">
    <label for="passwd_conf">Confirm Password</label>
    <input required type="password" class="<?= validInputClass('passwd_conf') ?> form-control" value="<?= $passwd_conf ?>" id="passwd_conf" name="passwd_conf" placeholder="Confirm Password">
    <?php if (isset($error->passwd_conf)) { 
        echo "<div class='invalid-feedback'>$error->passwd_conf</div>";
    } else {
        echo '<div class="valid-feedback">Looks good!</div>';
    } ?>
  </div>
  <div class="form-group form-check">
    <input required type="checkbox" class="<?= validInputClass('agree') ?> form-check-input" <?= $agree ? 'checked' : '' ?> id="agree" name="agree">
    <label class="form-check-label" for="agree">I agree to <a href="#">Term of services</a></label>
    <?php if (isset($error->agree)) { 
        echo "<div class='invalid-feedback' style='display: block'>$error->agree</div>";
    } ?>
  </div>
  <button type="submit" class="btn btn-primary">Sign Up</button>
<div>
<small>
Already have an account?
<a href="login.php">
Login here
</a>
</small>
</div>
</form>
</div>
</div>

</div>

<script src="js/main.js"></script>
</body>
</html>