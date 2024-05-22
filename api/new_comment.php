<?php
include "../include/bootstrap.php";

if(!isset($_SESSION["user"])) {
    echo generateApiError(401, "unauthorized", "you need to be logged in to use this endpoint");
    die();
}

if(!isset($_POST["parent"]) || !isset($_POST["text"]) || !isset($_POST["threadid"])) {
    echo generateApiError(400, "bad request", "one or more required fields are missing");
    die();
}

$parent     = $_POST["parent"];
$text       = $_POST["text"];
$threadID   = $_POST["threadid"];

if(!validateArray(array( [ "value" => $text, "min" => 5, "max" => 250 ] ))) {
    echo generateApiError(400, "bad request", "one or more required fields are not correctly validated");
    die();
}

echo generateApiResponse(createComment($text, $parent, $threadID));