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
    $stmt->bindParam(":uid", $uid);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedPass);
    $stmt->execute();
}

function getUser($email) {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function updateUserPassword($email, $password) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $db = connect();
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
}

function insertPswRegen($email) {
    $regenId = uniqid("psw", true);
    $db = connect();
    
    # Vi låter återställningskoden "leva" i 30 minuter.
    $stmt = $db->prepare("INSERT INTO resets VALUES(:id, :email, datetime('now', '+00:30'))");
    $stmt->bindParam(":id", $regenId);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    return $regenId;
}

function getPswRegen($token) {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM resets WHERE id = :id AND expires >= datetime('now')");
    $stmt->bindParam(":id", $token);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function deletePswRegen($token) { 
    $db = connect();
    $stmt = $db->prepare("DELETE FROM resets WHERE id = :id");
    $stmt->bindParam(":id", $token);
    $stmt->execute();
}

function getImage($id) {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM uploaded WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createImage($name, $width, $height) {
    $id = uniqid("image");
    // "CREATE TABLE IF NOT EXISTS uploaded (id TEXT PRIMARY KEY, filepath TEXT NOT NULL, width NUMBER NOT NULL, height NUMBER NOT NULL);",
    $db = connect();
    $stmt = $db->prepare("INSERT INTO uploaded VALUES(:id, :filename, :width, :height)");
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":filename", $name);
    $stmt->bindParam(":width", $width);
    $stmt->bindParam(":height", $height);

    $stmt->execute();
    return $id;
}

function createThread($title, $text, $imageID, $eId = null) {
    $id = uniqid("thread");

    if(isset($eId)) {
        $id = $eId;
    }

    $authorId = $_SESSION["user"]["id"];
    $db = connect();
    // "CREATE TABLE IF NOT EXISTS threads (id TEXT PRIMARY KEY, author TEXT NOT NULL, title TEXT NOT NULL, content TEXT NOT NULL, created TEXT NOT NULL, image TEXT, FOREIGN KEY(author) REFERENCES users(id), FOREIGN KEY(image) REFERENCES uploaded(id));",
    $stmt = $db->prepare("INSERT OR REPLACE INTO threads VALUES(:id, :authorID, :title, :text, :created, :image)");
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":authorID", $authorId);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":text", $text);
    $stmt->bindParam(":created", time());
    $stmt->bindParam(":image", $imageID);

    $stmt->execute();
    return $id;
}

function getThread($id) {
    $sql = "
    SELECT t.id, t.title, t.content, t.created, t.author, upl.id as imageid, upl.width, upl.height, u.username
    FROM threads AS t
    LEFT JOIN uploaded AS upl
    ON t.image = upl.id
    LEFT JOIN users AS u
    ON u.id = t.author
    WHERE t.id = :threadID;
    ";
    $db = connect();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":threadID", $id);

    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function getNewThreads() {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM threads ORDER BY created DESC LIMIT 5");

    $stmt->execute();
    return $stmt->fetchAll();
}