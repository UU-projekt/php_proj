<?php
	include "./include/bootstrap.php";

    $title  = "";
    $text   = "";
    $edit   = false;

    if(isset($_GET["edit"])) {
        $t = getThread($_GET["edit"]);
        if($t["author"] != $_SESSION["user"]["id"]) {
            setError("401", "Nej", "fyyyyyy");
            header("location: submit.php");
            die();
        }

        $edit = true;
        $title  = $t["title"];
        $text   = $t["content"];
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title>test</title>
		<link rel="stylesheet" href="./css/index.css">
	</head>
	<body class="roboto-regular stack center">
		<?php include "./include/views/_navbar.php" ?>
		
        <div class="layout">
            <?php include "./include/views/_sidebar.php" ?>

            <div class="content">
                <h1 class="fredoka-700 fsize-2em">Skapa tråd</h1>
                <?php include "./include/views/_info_bar.php" ?>
                <form method="POST" class="stack gap-large" action="/api/new_thread.php" enctype="multipart/form-data">

                    <div class="input_group">
                        <label for="title">Titel</label>
                        <input value="<?= strip_tags($title) ?>" maxlength="50" minlength="3" placeholder="Charmig titel här" type="text" name="title" required/>
                    </div>

                    <div class="input_group">
                        <label for="text">Text</label>
                        <textarea minlength="10" maxlength="2000" placeholder="Massa text här" name="text"><?= strip_tags($text) ?></textarea>
                    </div>

                    <div class="input_group">
                        <label for="image">Bild</label>
                        <img src="" />
                        <input type="file" accept=".gif, .png, .jpg" name="image" />
                    </div>

                    <?php if($edit): ?>
                        <input type="hidden" name="edit" value="<?= $_GET["edit"] ?>" />
                    <?php endif; ?>

                    <div class="btn-row">
                        <input class="btn branding" type="submit" value="<?= $edit ? "redigera" : "posta" ?>" />
                    </div>
                </form>
            </div>
        </div>
        <script src="/js/form.js"></script>
	</body>
</html>
