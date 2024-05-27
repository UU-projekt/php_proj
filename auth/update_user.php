<?php
include "../include/bootstrap.php";

if(!isset($_SESSION["user"])) {
    setError(401, "oops", "du måste logga in för detta");
    header("Location: ../user.php");
    die();
}

$email      = $_POST["email"];
$username   = $_POST["username"];

if(!isset($email, $username)) {
    setError(400, "Missing property");
    header("Location: ../user.php");
    die();
}


if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setError(400, "Validationsfel", "Epostadressen är felformaterad");
    header("Location: ../user.php");
    die();
}

if(strlen($username) < 4 || strlen($username) > 40) {
    setError(400, "Validationsfel", "Ditt användarnamn måste innehålla åtminstånde 5 tecken utan att överstiga 40");
    header("Location: ../user.php");
    die();
}

$alr = getUser($email);

if(!empty($alr)) {
    if($alr["email"] != $_SESSION["user"]["email"]) {
        setError(400, "Upptaget", "Denna email är redan i bruk");
        header("Location: ../user.php");
        die();
    }
}

try {
    insertUser($username, $_SESSION["user"]["password"], $email, $_SESSION["user"]["id"]);
    $_SESSION["user"]["username"] = $username;
    $_SESSION["user"]["email"] = $email;
    header("Location: ../user.php");
} catch(Exception $e) {
    setError(500, "ajajajaja", $e->getMessage());
    header("Location: ../user.php");
    die();
}