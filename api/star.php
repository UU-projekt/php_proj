<?php
include "../include/bootstrap.php";

if(!isset($_SESSION["user"])) {
    echo generateApiError(401, "not authorised", "booooo");
    die();
}

function handlePost() {
    if(!isset($_POST["threadid"])) {
        echo generateApiError(400, "bad request", "booooo");
        die();
    }

    
    if(isset($_POST["method"])) {
        unstarThread($_SESSION["user"]["id"], $_POST["threadid"]);
    } else {
        starThread($_SESSION["user"]["id"], $_POST["threadid"]);
    }

}

function handleGet() {
    if(!isset($_GET["id"])) {
        echo generateApiError(400, "bad request", "booooo");
        die();
    }

    echo generateApiResponse(getStarredThread($_SESSION["user"]["id"], $_GET["id"]));
}


try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "POST":
            handlePost();
            break;
        case "GET":
            handleGet();
            break;
    }
} catch(Exception $e) {
    echo generateApiError(500, "Something went wrong", $e->getMessage());
    die();
}