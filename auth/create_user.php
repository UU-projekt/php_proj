<?php
include "../include/bootstrap.php";
$email      = $_POST["email"];
$password   = $_POST["password"];
$username   = $_POST["username"];

if(!isset($email, $password, $username)) {
    setError(400, "Missing property");
    header("Location: ../auth/register.php");
    die();
}


if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setError(400, "Validationsfel", "Epostadressen är felformaterad");
    header("Location: ../auth/register.php");
    die();
}

if(strlen($password) < 4 || strlen($password) > 40) {
    setError(400, "Validationsfel", "Ditt lösenord måste innehålla åtminstånde 5 tecken utan att överstiga 40");
    header("Location: ../auth/register.php");
    die();
}

if(strlen($username) < 4 || strlen($username) > 40) {
    setError(400, "Validationsfel", "Ditt användarnamn måste innehålla åtminstånde 5 tecken utan att överstiga 40");
    header("Location: ../auth/register.php");
    die();
}

try {
    insertUser($username, $password, $email);
} catch(Exception $e) {
    setError(409, "Kunde inte skapa användare", "dubbelkolla att du inte redan har ett konto med samma epost");
    header("Location: ../auth/register.php");
    die();
}


header("Location: ../auth/login.php?email=" . $email);
die();