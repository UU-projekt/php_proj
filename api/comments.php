<?php
include "../include/bootstrap.php";

if(!isset($_GET["parent"])) {
    echo generateApiError(400, "bad request", "one or more required fields are missing");
    die();
}

try {
    $r = getComments($_GET["parent"]);
    echo generateApiResponse($r);
} catch(Exception $e) {
    echo generateApiError(500, "issue", $e->getMessage());
    die();
}