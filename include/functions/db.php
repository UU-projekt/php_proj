<?php

function connect() {
    if(isset($pdo)) return $pdo;

    $dbFile = getProperPath("the_base_of_data.db", "/db/"); # REMOVE THIS HARDCODED VALUE YOU ABSOLUTE FAILURE OF A MAN!!!!!!!!!!!!!!!!!!!!!
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->exec("PRAGMA recursive_triggers = on;");

    return $pdo;
}

function ensureTables() {
    $db = connect();
    $commands = [
        "CREATE TABLE IF NOT EXISTS uploaded (id TEXT PRIMARY KEY, filepath TEXT NOT NULL, width NUMBER NOT NULL, height NUMBER NOT NULL);",
        "CREATE TABLE IF NOT EXISTS users (id TEXT PRIMARY KEY, email TEXT NOT NULL UNIQUE, username TEXT NOT NULL, password TEXT NOT NULL, avatar TEXT, FOREIGN KEY(avatar) REFERENCES uploaded(id));",
        "CREATE TABLE IF NOT EXISTS resets (id TEXT PRIMARY KEY, email TEXT NOT NULL, expires TEXT NOT NULL);",
        "CREATE TABLE IF NOT EXISTS threads (id TEXT PRIMARY KEY, author TEXT NOT NULL, title TEXT NOT NULL, content TEXT NOT NULL, html TEXT, created TEXT NOT NULL, image TEXT, FOREIGN KEY(author) REFERENCES users(id), FOREIGN KEY(image) REFERENCES uploaded(id));",
        "CREATE TABLE IF NOT EXISTS comments (id TEXT PRIMARY KEY, parent TEXT NOT NULL, author TEXT NOT NULL, content TEXT NOT NULL, html TEXT, created TEXT NOT NULL, threadID TEXT NOT NULL, children NUMBER DEFAULT 0, FOREIGN KEY(author) REFERENCES users(id), FOREIGN KEY(threadID) REFERENCES threads(id));",
        "CREATE TABLE IF NOT EXISTS starredThreads (threadID TEXT NOT NULL, userID TEXT NOT NULL, PRIMARY KEY(userID, threadID), FOREIGN KEY(threadID) REFERENCES threads(id), FOREIGN KEY(userID) REFERENCES users(id));",
        "INSERT INTO users VALUES('deleteduser', 'N/A', 'raderat', 'N/A', '')",

        "
            CREATE TRIGGER IF NOT EXISTS rmchildren AFTER DELETE ON threads
            BEGIN
                DELETE FROM comments
                WHERE threadID = OLD.id;
            END;
        ",

        "
            CREATE TRIGGER IF NOT EXISTS rmuploads AFTER DELETE ON threads
            BEGIN
                DELETE FROM uploaded
                WHERE id = OLD.image;
            END;
        ",

        // Lavin :D
        "
        CREATE TRIGGER IF NOT EXISTS updchildsrec AFTER UPDATE ON comments
        BEGIN
            UPDATE comments SET children = children + 1 WHERE id = NEW.parent;
        END;
        ",

        // Denna action ökar förälderns "children" variabel med 1 som startar ravinen åvan
        "
            CREATE TRIGGER IF NOT EXISTS updchilds AFTER INSERT ON comments
            BEGIN
                UPDATE comments SET children = children + 1 WHERE id = NEW.parent;
            END;
        "

    ];

    foreach ($commands as $command) {
        $db->exec($command);
    }
}

function insertUser($username, $password, $email, $uuid = null) {
    $uid = uniqid("user");
    if(isset($uuid)) $uid = $uuid;

    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
    $db = connect();
    $stmt = $db->prepare("INSERT OR REPLACE INTO users VALUES(:uid, :email, :username, :password, null)");
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
    $stmt = $db->prepare("INSERT OR REPLACE INTO threads VALUES(:id, :authorID, :title, :text, :html, :created, :image)");
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":authorID", $authorId);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":text", $text);
    $stmt->bindParam(":html", generateHtml($text));
    $stmt->bindParam(":created", time());
    $stmt->bindParam(":image", $imageID);

    $stmt->execute();
    return $id;
}

function deleteThread($id) {
    $db = connect();

    $stmt = $db->prepare("DELETE FROM threads WHERE id = :id");
    $stmt->bindParam(":id", $id);

    $stmt->execute();
}

function createComment($text, $parent, $threadID, $eId = null) {
    $id = uniqid("comment");

    if(isset($eId)) {
        $id = $eId;
    }

    $authorId = $_SESSION["user"]["id"];
    $db = connect();
    // "CREATE TABLE IF NOT EXISTS comments (id TEXT PRIMARY KEY, parent TEXT NOT NULL, author TEXT NOT NULL, content TEXT NOT NULL, html TEXT, created TEXT NOT NULL, FOREIGN KEY(author) REFERENCES users(id));",
    $stmt = $db->prepare("INSERT OR REPLACE INTO comments VALUES(:id, :parent, :authorID, :text, :html, :created, :threadid, 1)");
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":authorID", $authorId);
    $stmt->bindParam(":parent", $parent);
    $stmt->bindParam(":text", $text);
    $stmt->bindParam(":threadid", $threadID);
    $asHtml = generateHtml($text);
    $created = time();
    $stmt->bindParam(":html", $asHtml);
    $stmt->bindParam(":created", $created);

    $stmt->execute();
    return [ "id" => $id, "username" => $_SESSION["user"]["username"], "userid" => $_SESSION["user"]["id"], "content" => $text, "html" => $asHtml, "created" => $created ];
}


function getThread($id) {
    $sql = "
    SELECT t.id, t.title, t.content, t.html, t.created, t.author, upl.id as imageid, upl.width, upl.height, u.username, u.id as userid, (SELECT SUM(children) FROM comments WHERE parent = t.id) AS children
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

function searchThread($query) {
    $sql = "
    SELECT t.id, t.title, t.content, t.html, t.created, t.author, upl.id as imageid, upl.width, upl.height, u.username, u.id as userid, (SELECT COUNT(*) FROM starredThreads WHERE threadID = t.id) AS stars, (SELECT SUM(children) FROM comments WHERE parent = t.id) AS children
    FROM threads AS t
    LEFT JOIN uploaded AS upl
    ON t.image = upl.id
    LEFT JOIN users AS u
    ON u.id = t.author
    WHERE t.content LIKE '%' || :query || '%' OR t.title LIKE '%' || :query || '%';
    ";
    $db = connect();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":query", $query);

    $stmt->execute();
    return $stmt->fetchAll();
}

function getHighStarThreads() {
    $sql = "
    SELECT t.id, t.title, t.content, t.html, t.created, t.author, upl.id as imageid, upl.width, upl.height, u.username, u.id as userid, (SELECT COUNT(*) FROM starredThreads WHERE threadID = t.id) AS stars, (SELECT SUM(children) FROM comments WHERE parent = t.id) AS children
    FROM threads AS t
    LEFT JOIN uploaded AS upl
    ON t.image = upl.id
    LEFT JOIN users AS u
    ON u.id = t.author
    ORDER BY stars DESC LIMIT 10;
    ";
    $db = connect();
    $stmt = $db->prepare($sql);

    $stmt->execute();
    return $stmt->fetchAll();
}

function getHighCommentThreads() {
    $sql = "
    SELECT t.id, t.title, t.content, t.html, t.created, t.author, upl.id as imageid, upl.width, upl.height, u.username, u.id as userid, (SELECT COUNT(*) FROM starredThreads WHERE threadID = t.id) AS stars, (SELECT SUM(children) FROM comments WHERE parent = t.id) AS children
    FROM threads AS t
    LEFT JOIN uploaded AS upl
    ON t.image = upl.id
    LEFT JOIN users AS u
    ON u.id = t.author
    ORDER BY children DESC LIMIT 10;
    ";
    $db = connect();
    $stmt = $db->prepare($sql);

    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserThreads($uid) {
    $sql = "
    SELECT t.id, t.title, t.content, t.html, t.created, t.author, upl.id as imageid, upl.width, upl.height, u.username, u.id as userid, (SELECT COUNT(*) FROM starredThreads WHERE threadID = t.id) AS stars, (SELECT SUM(children) FROM comments WHERE parent = t.id) AS children
    FROM threads AS t
    LEFT JOIN uploaded AS upl
    ON t.image = upl.id
    LEFT JOIN users AS u
    ON u.id = t.author
    WHERE u.id = :userid
    ORDER BY t.created;
    ";
    $db = connect();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":userid", $uid);

    $stmt->execute();
    return $stmt->fetchAll();
}

function starThread($userid, $threadid) {
    $id = uniqid("starred");
    $db = connect();
    $stmt = $db->prepare("INSERT INTO starredThreads VALUES(:threadid, :userid)");
    $stmt->bindParam(":threadid", $threadid);
    $stmt->bindParam(":userid", $userid);

    $stmt->execute();
    return $id;
}

function unstarThread($userid, $threadid) {
    $id = uniqid("starred");
    $db = connect();
    $stmt = $db->prepare("DELETE FROM starredThreads WHERE threadID = :threadid AND userID = :userid");
    $stmt->bindParam(":threadid", $threadid);
    $stmt->bindParam(":userid", $userid);

    $stmt->execute();
    return $id;
}

function getStarredThreads($userid) {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM starredThreads AS st JOIN threads as t ON t.id = st.threadID WHERE st.userID = :userid");
    $stmt->bindParam(":userid", $userid);

    $stmt->execute();
    return $stmt->fetchAll();
}

function getStarredThread($userid, $threadid) {
    $db = connect();
    $stmt = $db->prepare("SELECT * FROM starredThreads WHERE threadID = :threadid AND userID = :userid");
    $stmt->bindParam(":userid", $userid);
    $stmt->bindParam(":threadid", $threadid);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getComments($parent) {
    $sql = "
    SELECT c.id, c.parent, c.content, c.html, c.created, c.author, u.username, u.id as userid, c.children as children
    FROM comments AS c
    LEFT JOIN users AS u
    ON u.id = c.author
    WHERE c.parent = :parent
    ORDER BY c.created DESC";

    $db = connect();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":parent", $parent);

    $stmt->execute();
    return $stmt->fetchAll();
}

function getComment($id) {
    $sql = "
        SELECT t.author AS thread_userid, c.author AS comment_userid, c.id
        FROM comments AS c
        JOIN threads AS t
        ON t.id = c.threadID
        WHERE c.id = :id
    ";

    $db = connect();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $id);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteComment($id, $reason) {
    $db = connect();
    $stmt = $db->prepare("UPDATE comments SET html = :html, author = 'deleteduser' WHERE id = :id");
    $stmt->bindParam(":html", $reason);
    $stmt->bindParam(":id", $id);
    
    $stmt->execute();
    return "deleted";
}

//ensureTables();
