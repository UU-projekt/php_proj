<?php

function generateBaseRequest($data) {
    $URL = "https://api.postmarkapp.com/email/withTemplate";
    $OPTIONS = [
        'http' => [
#                                                                                                          Aldrig bra att ha API-nyckel direkt i koden men jag är farlig
            #                                                                                                         ⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊⇊
            "header" => array("Accept: application/json", "Content-Type: application/json", "X-Postmark-Server-Token: c6a20700-ce46-42e1-9283-c1e936c00954"),
            "method"=> "POST",
            "content"=> json_encode($data),
        ]
    ];

    $context = stream_context_create($OPTIONS);
    $result = file_get_contents($URL, false, $context);

    if($result === false) {
        throw new Exception("Kunde inte skicka mailet");
    }

}

function sendPswResetEmail($email, $username, $resetLink) {
    $data = [
        "From" => "system.redidit@familyfriendly.xyz",
        "To" => $email,
        "TemplateAlias" => "password-reset",
        "TemplateModel" => [
            "product_url" => "http://localhost/",
            "product_name" => "Redidit",
            "name" => $username,
            "action_url" => $resetLink,
            "company_name" => "Redidit AB",
            "company_address" => "Ekonomikum",
            "operating_system" => "Hej",
            "browser_name" => "jeff",
            "support_url" => "https://familyfriendly.xyz"
        ]
    ];

    return generateBaseRequest($data);
}