<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$path = $_SERVER['DOCUMENT_ROOT'];

function getProperPath($name, $dir = "/include/functions/") {
    $path = $_SERVER['DOCUMENT_ROOT'];
    return $path . $dir . $name;
}

include getProperPath("apiHelpers.php");
include getProperPath("db.php");
include getProperPath("errorState.php");
include getProperPath("mail.php");
include getProperPath("markdown.php");