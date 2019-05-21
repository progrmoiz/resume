<?php
include_once 'db.php';
require_once 'functions.php';

session_start();

if (!isUserLoggedIn()) {
    header('Location: login.php');
}

$user_id = $_SESSION['user'];

$target_dir = "user_files/";
$target_file = $target_dir . basename($_FILES["resume"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$target_filename = uniqid() . '.' . $fileType;
$target_file = $target_dir . $target_filename;
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['resume']['tmp_name']);

    if ($mime == 'application/pdf') {
        // echo 'This is a PDF file!';
        $uploadOk = 1;
    } else {
        // echo "File is not pdf.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["resume"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
print_r($user_id);

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        echo "The file " . basename($_FILES["resume"]["name"]) . " has been uploaded.";
        $sql = "INSERT INTO userFile (user_id, resume) VALUES (:user_id, :resume)";
        $handle = $db->prepare($sql);
        $handle->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $handle->bindValue(':resume', $target_filename);
        $handle->execute();
        header("Location: index.php");
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
