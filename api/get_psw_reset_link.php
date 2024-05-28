<?php
include "../include/bootstrap.php";

if(!isset($_SESSION["user"])) {
    echo generateApiError(401, "unathorized", "you need to be logged in to use this endpoint");
    die();
}

try {
    $code = insertPswRegen($_SESSION["user"]["email"]);
    echo generateApiResponse($code);
} catch(Exception $e) {
    echo generateApiError(500, "psw_regen_error", $e->getMessage());
}