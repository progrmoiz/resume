<?php
include_once 'db.php';
require_once 'functions.php';

session_start();

if (!isUserLoggedIn()) {
    header('Location: login.php');
}

$user_id = $_SESSION['user'];
$user = getUserbyId($user_id);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="jumbotron">
<div class="container pt-5">
<?php
if ($user->is_admin) {
    echo '<p class="mb-0"><small>You are admin</small></p>';
}
if (isUserLoggedIn()) {
    echo "<h1 class='mb-4 display-4'>Welcome, $user->name <small>(<a href='logout.php'>Logout</a>)</small></h1>";
}
?>
<h3 class="my-4 lead">Upload your resume.</h3>
<div class="row">
<div class="col-6">
<form action="upload.php" method="post" enctype="multipart/form-data">
    <div class="custom-file my-2">
        <input type="file" class="custom-file-input" id="resume" name="resume" accept=".pdf">
        <label class="custom-file-label" for="resume">Choose file</label>
    </div>
    <input class="btn btn-primary" type="submit" value="Submit" name="submit">
</form>
</div>
</div>
</div>
</div>

<div class="container">

<table class="table table-striped mt-5">
<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Id</th>
        <th scope="col">User Id</th>
        <th scope="col">Candidate Name</th>
        <th scope="col">Resume</th>
    </tr>
</thead>
<tbody>
<?php
if ($user->is_admin == 1) {
    $sql = 'SELECT userFile.id, userFile.user_id, userFile.resume, userAccount.name FROM userFile INNER JOIN userAccount ON userAccount.id = userFile.user_id';
} else {
    $sql = 'SELECT userFile.id, userFile.user_id, userFile.resume, userAccount.name FROM userFile INNER JOIN userAccount ON userAccount.id = userFile.user_id WHERE user_id = :user_id';
}
$handle = $db->prepare($sql);
$handle->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$handle->execute();
$result = $handle->fetchAll(\PDO::FETCH_OBJ);

if (!empty($result)) {
    foreach ($result as $_k => $row) {
        $_k += 1;
        echo "<tr>";
        echo "<td>$_k</td>";
        echo "<td>$row->id</td>";
        echo "<td>$row->user_id</td>";
        echo "<td>$row->name</td>";
        echo "<td><a href='user_files/$row->resume'>$row->resume</a></td>";
        echo "</tr>";
    }
} else {
    echo '<tr><td colspan="4">No Results</td></tr>';
}
?>
</tbody>
</table>
</div>

<script src="js/main.js"></script>
</body>
</html>