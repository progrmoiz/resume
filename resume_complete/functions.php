<?php

function isUserLoggedIn() {
    return isset($_SESSION['user']) && $_SESSION['user'] != '';
}

function getUserbyId($id) {
    global $db;

    $sql = "SELECT * FROM `userAccount` WHERE `id` = :user_id";

    $handle = $db->prepare($sql);
    $handle->bindValue(':user_id', $id, PDO::PARAM_INT);
    $handle->execute();

    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    return !empty($result) ? $result[0] : null;
}