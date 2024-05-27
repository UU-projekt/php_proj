<?php
include "../include/bootstrap.php";

    $url = "../index.php";
    if(isset($_GET["url"])) $url = $_GET["url"];
    else $url = strip_tags($url);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php require "../include/views/_head.php" ?>
    <link rel="stylesheet" href="../css/register/index.css">
</head>
<body class="roboto-regular center">    
    <div id="logo"></div>
    <div id="content" class="stack gap-large">
        <?php include "../include/views/_logo.php"; ?>

        <?php include "../include/views/_info_bar.php"; ?>

        <form class="stack gap-small" method="POST" action="login_user.php">
            <div class="input_group">
                <label for="email">Email</label>
                <input value="<?= isset($_GET["email"]) ? strip_tags($_GET["email"]) : ""; ?>" placeholder="Jakob.Ulfsson@student.uu.se" type="email" name="email" required/>
            </div>

            <div class="input_group">
                <label for="password">Lösenord</label>
                <input placeholder="Lösenord" type="password" minlength="5" maxlength="40" name="password" required/>
            </div>

            <input type="hidden" name="url" value="<?= $url ?>" />
            <input class="btn branding" type="submit" value="Logga in" />
        </form> 
        <a href="/auth/register.php" class="btn tetriary">Skapa ett konto</a>
        <small>Glömt ditt lösenord? <a href="/auth/reset_password.php">Återställ lösenord</a>.</small>
    </div>
</body>
</html>