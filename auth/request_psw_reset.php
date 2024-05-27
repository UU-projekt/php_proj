<?php
include "../include/bootstrap.php";

$email      = $_POST["email"];

if(!isset($email)) {
    setError(400, "Missing property");
    header("Location: ../auth/reset_password.php");
    die();
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setError(400, "Validationsfel", "Epostadressen är felformaterad");
    header("Location: ../auth/reset_password.php");
    die();
}

$user = getUser($email);

if(empty($user)) {
    setError(404, "...Spöklikt 👻", "Ett konto med denna epostadress finns ej.");
    header("Location: ../auth/reset_password.php");
    die();
}

try {
    $code = insertPswRegen($user["email"]);

    try {
        sendPswResetEmail($user["email"], $user["username"], "http://localhost/auth/set_password.php?t=" . $code);
        setError(200, "Kod skickad!", "Håll utkik i postlådan! Koden går ut om en halvtimme");
        header("Location: /auth/reset_password.php");
        die();
    } catch (Exception $e) {
        setError(500, "Kunda inte skicka email", "Tyvärr kunde vi inte skicka koden till dig. Prova igen senare");
        header("Location: ../auth/reset_password.php");
        die();
    }
    

    #setError(200, "Okidoki!", "Gå <a href='/auth/set_password.php?t=" . $code . "'>hit</a>");
    #header("Location: /auth/reset_password.php");
    #die();
} catch(Exception $e) {
    setError(500, "Server-fel", "kunde inte skapa lösenordsåterställning: " . $e->getMessage());
    header("Location: ../auth/reset_password.php");
    die();
}
