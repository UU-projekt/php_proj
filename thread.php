<?php
	include "./include/bootstrap.php";

    if(!isset($_GET["id"])) {
        header("location: /index.php");
        die();
    }

    $t = getThread($_GET["id"]);

    if(empty($t)) {
        header("location: /index.php");
        die();
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?= strip_tags($t["title"]) ?> - Redidit</title>
		<link rel="stylesheet" href="./css/index.css">
        <link rel="stylesheet" href="./css/thread.css">
	</head>
	<body class="roboto-regular stack center">
		<?php include "./include/views/_navbar.php" ?>
		
        <div class="layout">
            <?php include "./include/views/_sidebar.php" ?>
            <div class="content stack gap-huge">
                <div class="thread-details stack gap-medium">
                    <h1 class="fredoka-700 fsize-2em"><?= strip_tags($t["title"]) ?></h1>
                    <div class="thread-meta"> 
                        <p>postades <span title="<?= date("d/m/y H:i", $t["created"]) ?>"><?= date("d/m/y", $t["created"]) ?></span> av <b><?= $t["username"] ?></b>
                        <?php if(isset($_SESSION["user"]) && $t["author"] == $_SESSION["user"]["id"]): ?>
                        <small>(<a href="/submit.php?edit=<?= $t["id"] ?>">redigera tråd</a>)</small>
                        </p>
                        <?php endif; ?>
                    </div>
                    <article>
                        <section class="md">
                            <?= generateHtml($t["content"]) ?>
                        </section>
                        
                        <?php if(isset($t["imageid"])): ?>
                        <img src="/cdn/image.php?key=<?= $t["imageid"] ?>" />
                        <?php endif; ?>
                    </article>
                </div>

                <?php include "./include/views/_post-comment.php" ?>
                
                
            </div>
        </div>
	</body>
</html>
