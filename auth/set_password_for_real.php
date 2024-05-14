<?php
include "../include/bootstrap.php";

$password   = $_POST["password"];
$token      = $_POST["token"];


if(!isset($password, $token)) {
    setError(400, "Missing property");
    header("Location: /auth/set_password.php?reload=true");
    die();
}

if(strlen($password) < 4 || strlen($password) > 40) {
    setError(400, "Validationsfel", "Ditt lösenord måste innehålla åtminstånde 5 tecken utan att överstiga 40");
    header("Location: /auth/set_password.php?reload=true");
    die();
}

try {
    $resetRequest = getPswRegen($token);

    if(empty($resetRequest)) {
        setError(404, "Ogiltig kod", "denna kod finns inte i våran databas. Koden kan ha löpt ut");
        header("Location: /auth/set_password.php?reload=true");
        die();
    }

    try {
        updateUserPassword($resetRequest["email"], $password);
        deletePswRegen($token);
        header("Location: /auth/login.php?email=" . $resetRequest["email"]);
        die();
    } catch(Exception $e) {
        setError(500, "Något gick fel", "Det gick inte att uppdatera ditt lösenord :(");
        header("Location: /auth/set_password.php?reload=true");
        die();
    }
} catch (Exception $e) {
    setError(404, "Här var det tomt", "detta är inte en giltig länk för att återställa ditt lösenord");
    header("Location: /auth/set_password.php?reload=true");
    die();
}
