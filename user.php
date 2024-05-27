<?php
	include "./include/bootstrap.php";
    if(!isset($_SESSION["user"])) {
        setError(401, "Inte inloggad", "du behöver logga in innan du kan konfigurera din profil");
        header("Location: auth/login.php?url=/user.php");
        die();
    }

    $u = getUser($_SESSION["user"]["email"]);
    $userThreads = getUserThreads($u["id"]);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>test</title>
		<link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/register/index.css">
        <script src="js/user.js" defer type="module"></script>
	</head>
	<body class="roboto-regular stack containerthing">
		<?php include "./include/views/_navbar.php" ?>
		<div class="layout">
            <?php include "./include/views/_sidebar.php" ?>

            <div class="content stack gap-large">
                <h1>Tjenare, <?= $u["username"] ?></h1>

                <div class="btnrow">
                    <button onclick="changePassword()" class="btn branding">Byt Lösenord</button>
                    <a href="/auth/logout.php" class="btn tetriary">logga ut</a>
                </div>

                <form action="auth/update_user.php" method="POST">
                    <?php include "./include/views/_info_bar.php" ?>
                    <div class="input_group">
                        <label for="email">Email</label>
                        <input value="<?= strip_tags($u["email"]) ?>" placeholder="Jakob.Ulfsson@student.uu.se" type="email" name="email" required/>
                    </div>
                    <div class="input_group">
                        <label for="username">Username</label>
                        <input value="<?= strip_tags($u["username"]) ?>" placeholder="Jakob.Ulfsson@student.uu.se" minlength="3" maxlength="40" type="text" name="username" required/>
                    </div>

                    <input class="btn branding" type="submit" value="Uppdatera" />
                </form>

                <div>
                    <h2>Dina trådar</h2>
                    <div class="stack gap-large">
                        <?php foreach($userThreads as $r): ?>
                            <?= renderResult($r) ?>
                        <?php endforeach ?>
				</div>
			</div>
            </div>
        </div>
	</body>
</html>
