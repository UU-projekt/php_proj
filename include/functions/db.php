<?php

function connect() {
    if(isset($pdo)) return $pdo;

    $dbFile = "/var/www/html/db/the_base_of_data.db"; # REMOVE THIS HARDCODED VALUE YOU ABSOLUTE FAILURE OF A MAN!!!!!!!!!!!!!!!!!!!!!
    $pdo = new PDO("sqlite:$dbFile");
    return $pdo;
}

function ensureTables() {
    $db = connect();
    $commands = [
        "CREATE TABLE IF NOT EXISTS uploaded (id TEXT PRIMARY KEY, filepath TEXT NOT NULL, width NUMBER NOT NULL, height NUMBER NOT NULL);",
        "CREATE TABLE IF NOT EXISTS users (id TEXT PRIMARY KEY, email TEXT NOT NULL UNIQUE, username TEXT NOT NULL, password TEXT NOT NULL, avatar TEXT, FOREIGN KEY(avatar) REFERENCES uploaded(id));",
        "CREATE TABLE IF NOT EXISTS resets (id TEXT PRIMARY KEY, email TEXT NOT NULL, expires TEXT NOT NULL);",
        "CREATE TABLE IF NOT EXISTS threads (id TEXT PRIMARY KEY, author TEXT NOT NULL, title TEXT NOT NULL, content TEXT NOT NULL, created TEXT NOT NULL, image TEXT, FOREIGN KEY(author) REFERENCES users(id), FOREIGN KEY(image) REFERENCES uploaded(id));",
        "CREATE TABLE IF NOT EXISTS comments (id TEXT PRIMARY KEY, parent TEXT NOT NULL, author TEXT NOT NULL, content TEXT NOT NULL, created TEXT NOT NULL, FOREIGN KEY(parent) REFERENCES threads(id), FOREIGN KEY(author) REFERENCES users(id));"
    ];

    foreach ($commands as $command) {
        $db->exec($command);
    }
}

function insertUser($username, $password, $email) {
    $uid = uniqid("user");
    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
    $db = connect();
    $stmt = $db->prepare("INSERT INTO users VALUES(:uid, :email, :username, :password, null)");
    $stmt->bindParam(":uid", $uid, SQLITE3_TEXT);
    $stmt->bindParam(":email", $email, SQLITE3_TEXT);
    $stmt->bindParam(":username", $username, SQLITE3_TEXT);
    $stmt->bindParam(":password", $hashedPass, SQLITE3_TEXT);
    $stmt->execute();
}

function getUser($email) {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email, SQLITE3_TEXT);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function updateUserPassword($email, $password) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $db = connect();
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->bindParam(":email", $email, SQLITE3_TEXT);
    $stmt->bindParam(":password", $password, SQLITE3_TEXT);
    $stmt->execute();
}

function insertPswRegen($email) {
    $regenId = uniqid("psw", true);
    $db = connect();
    
    # Vi låter återställningskoden "leva" i 30 minuter.
    $stmt = $db->prepare("INSERT INTO resets VALUES(:id, :email, datetime('now', '+00:30'))");
    $stmt->bindParam(":id", $regenId, SQLITE3_TEXT);
    $stmt->bindParam(":email", $email, SQLITE3_TEXT);
    $stmt->execute();

    return $regenId;
}

function getPswRegen($token) {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM resets WHERE id = :id AND expires >= datetime('now')");
    $stmt->bindParam(":id", $token, SQLITE3_TEXT);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function deletePswRegen($token) { 
    $db = connect();
    $stmt = $db->prepare("DELETE FROM resets WHERE id = :id");
    $stmt->bindParam(":id", $token, SQLITE3_TEXT);
    $stmt->execute();
}

function getImage($id) {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM uploaded WHERE id = :id");
    $stmt->bindParam(":id", $id, SQLITE3_TEXT);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}