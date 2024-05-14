<?php
    include "./include/bootstrap.php";
    if(!isset($_SESSION["user"])) {
        setError(401, "Inte inloggad", "du behöver logga in innan du kan konfigurera din profil");
        header("Location: /auth/login.php?url=/user.php");
        die();
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Ditt konto</title>
        <?php require "./include/views/_head.php" ?>
        <link rel="stylesheet" href="/css/usersettings/index.css">
        <script type="module" src="/js/user.js" defer></script>
	</head>
	<body class="roboto-regular center">
		<?php include "./include/views/_navbar.php" ?>
        <div class="stack gap-medium">
            <div class="user">
                <img class="avatar" src="/cdn/image.php?key=<?= $_SESSION["user"]["avatar"] ?>&default=true" />
                <h3><?= $_SESSION["user"]["username"] ?></h3>
            </div>


        </div>
	</body>
</html>
