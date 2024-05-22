<?php
include "../include/bootstrap.php";

if(!isset($_SESSION["user"])) {
    echo generateApiError(401, "not authorised", "booooo");
    die();
}

try {
    if(isset($_POST["thread"])) {
        $id = $_POST["thread"];
    
        $t = getThread($id);
    
        if(empty($t)) {
            echo generateApiError(404, "Not found", "Nice try tho :D");
            die();
        }
    
        if($t["author"] != $_SESSION["user"]["id"]) {
            echo generateApiError(401, "Not even now", "Nice try tho :D");
            die();
        }

        deleteThread($id);
        echo generateApiResponse("deleted");
    }

    if(isset($_POST["comment"])) {
        $c = getComment($_POST["comment"]);
        $uid = $_SESSION["user"]["id"];

        if($c["thread_userid"] == $uid) {
            deleteComment($_POST["comment"], "[raderat av trådskaparen]");
        } elseif($c["comment_userid"] == $uid) {
            deleteComment($_POST["comment"], "[raderat]");
        } else {
            echo generateApiError(401, "nah", "not allowed");
        }

        echo generateApiResponse("deleted");
    }

} catch(Exception $e) {
    echo generateApiError(500, "oopsie poopsie", $e->getMessage());
}



//delete