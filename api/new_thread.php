<?php
include "../include/bootstrap.php";

if(!isset($_SESSION["user"])) {
    echo generateApiError(401, "unauthorized", "you need to be logged in to use this endpoint");
    die();
}

if(!isset($_POST["title"]) || !isset($_POST["text"])) {
    echo generateApiError(400, "bad request", "one or more required fields are missing");
    die();
}

$id = null;

if(isset($_POST["edit"])) {
    $id = $_POST["edit"];

    $t = getThread($id);

    if(empty($t)) {
        echo generateApiError(404, "Not found", "Nice try tho :D");
        die();
    }

    if($t["author"] != $_SESSION["user"]["id"]) {
        echo generateApiError(401, "Not even now", "Nice try tho :D");
        die();
    }
}

$title  = $_POST["title"];
$text   = $_POST["text"];
$imgid = null;

if(isset($_FILES["image"]) && !$_FILES["image"]["error"] && !empty($_FILES["image"]["tmp_name"])) {
    $img        = $_FILES["image"];
    $img_path   = $img["tmp_name"];
    $ext = pathinfo($img["name"])["extension"];

    if(!in_array($ext, array("gif", "png", "jpg"))) {
        echo generateApiError(400, "Image", "The file attached was not of accepted type: " . $img_path);
        die();
    }

    $imgsize = getimagesize($img_path);

    $newFileName = uniqid("uploaded_", true) . "." . $ext;

    move_uploaded_file($img_path, getProperPath($newFileName, "/img/uploaded/"));

    $imgid = createImage("uploaded/" . $newFileName, $imgsize[0], $imgsize[1]);
}

if(!validateArray(array( [ "value" => $title, "min" => 3, "max" => 50 ], [ "value" => $text, "min" => 10, "max" => 2000 ] ))) {
    echo generateApiError(400, "bad request", "one or more required fields are not correctly validated");
    die();
}

$res;

if(isset($id)) {
    $res = createThread($title, $text, $imgid, $id);
} else {
    $res = createThread($title, $text, $imgid);
}

header("Location:" . "/thread.php?id=" . $res);