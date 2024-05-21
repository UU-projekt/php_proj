<?php

function validateArray($arr) {
    foreach($arr as $item) {
        $min    = $item["min"];
        $max    = $item["max"];
        $value  = $item["value"];

        if(gettype($value) === "string") {
            $value = strlen($value);
        }

        if($min > $value || $max < $value) return false;
    }

    return true;
}

function generateApiError($code, $title, $description) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);

    $data = [
        "code" => $code,
        "data" => [
            "title" => $title,
            "description" => $description
        ]
    ];

    return json_encode($data);
}

function generateApiResponse($data) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);

    $data = [
        "code" => 200,
        "data" => $data
    ];

    return json_encode($data);
}