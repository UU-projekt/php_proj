<?php
include "../include/bootstrap.php";
$email      = $_POST["email"];
$password   = $_POST["password"];
$url        = $_POST["url"];

if(!isset($email, $password)) {
    setError(400, "Missing property");
    header("Location: ../auth/login.php");
    die();
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setError(400, "Validationsfel", "Epostadressen är felformaterad");
    header("Location: ../auth/login.php");
    die();
}

if(strlen($password) < 4 || strlen($password) > 40) {
    setError(400, "Validationsfel", "Ditt lösenord måste innehålla åtminstånde 5 tecken utan att överstiga 40");
    header("Location: ../auth/login.php");
    die();
}

$user = getUser($email);

if(empty($user)) {
    setError(404, "...Spöklikt 👻", "Ett konto med denna epostadress finns ej. Försöker du <a href=\"/auth/register.php\">registrera</a> ett konto?");
    header("Location: ../auth/login.php");
    die();
}

if(password_verify($password, $user["password"])) {
    unset($user["password"]);
    $_SESSION["user"] = $user;
    header("Location: " . $url);
    die();
} else {
    setError(401, "Fel lösenord", "Lösenordet du angav var fel.");
    header("Location: ../auth/login.php");
    die();
}