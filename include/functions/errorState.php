<?php

function setError($code, $message, $detailed="") {
    $_SESSION["error"]["code"] = $code;
    $_SESSION["error"]["message"] = $message;
    $_SESSION["error"]["detailed"] = $detailed;
    return true;
};
