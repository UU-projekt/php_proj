<?php
include "../include/bootstrap.php";

if(!isset($_GET["key"])) {
    echo generateApiError(400, "Bad Request", "parameter value key is required (we need an identifier to show an image. Duh)");
    die();
}

if(isset($_GET["default"])) $default = $_GET["default"];

try {
    $image = getImage($_GET["key"]);
} catch(Exception $e) {
    echo generateApiError(500, "Something went wrong", $e->getMessage());
    die();
}

header("Content-Type: image/png");

function resolveFilePath($filename, $dir = "avatars") {
    $path = "../img";
    if(isset($dir)) $path = $path . "/" . $dir . "/";
    $path = $path . $filename;

    return $path;
}

if(!empty($image)) {
    
}
elseif(isset($default) && $default === "true") {
    echo readfile(resolveFilePath("default.png"));
} else {
    echo generateApiError(404, "Not Found", "image with id '" . $_GET["key"] . "' was not found.");
}

