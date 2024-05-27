<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$path = $_SERVER['DOCUMENT_ROOT'];


// creds: https://stackoverflow.com/questions/1091107/how-to-join-filesystem-path-strings-in-php
function join_paths() {
    $paths = array();

    foreach (func_get_args() as $arg) {
        if ($arg !== '') { $paths[] = $arg; }
    }

    return preg_replace('#/+#','/',join('/', $paths));
}

// Leta efter root-mappen som innehåller index.php
function findBasePath($dir = null) {
    if(!isset($dir)) {
        $dir = dirname(__DIR__);
    }

    $joined = join_paths($dir, ".projectroot");
    if(file_exists($joined)) return $dir;
    else return findBasePath(dirname("../" . $dir));
}

function getProperPath($name, $dir = "/include/functions/") {
    $path = findBasePath();
    return $path . $dir . $name;
}

include getProperPath("apiHelpers.php");
include getProperPath("db.php");
include getProperPath("errorState.php");
include getProperPath("mail.php");
include getProperPath("markdown.php");
include getProperPath("renderThread.php");