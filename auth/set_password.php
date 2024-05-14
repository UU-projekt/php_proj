<?php
    include "../include/bootstrap.php";

    if(!isset($_GET["reload"])) {
        if(!isset($_GET["t"])) {
            setError(401, "Ogiltig länk", "länken saknar återställnings-id. En giltig ser ut som <code>länk.com<b>?t=pswblahblah</b></code>");
            header("Location: /auth/set_password.php?reload=true");
            die();
        }
    
        $token = strip_tags($_GET["t"]);
    
        try {
            $resetRequest = getPswRegen($token);

            if(empty($resetRequest)) {
                setError(404, "Ogiltig kod", "denna kod finns inte i våran databas. Koden kan ha löpt ut");
                header("Location: /auth/set_password.php?reload=true");
                die();
            }
        } catch (Exception $e) {
            setError(404, "Här var det tomt", "detta är inte en giltig länk för att återställa ditt lösenord");
            header("Location: /auth/set_password.php?reload=true");
            die();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php require "../include/views/_head.php" ?>
    <link rel="stylesheet" href="/css/register/index.css">
</head>
<body class="roboto-regular center">    
    <div id="logo"></div>
    <div id="content" class="stack gap-large">
        <?php include "../include/views/_logo.php"; ?>
        <?php include "../include/views/_info_bar.php"; ?>

        <form class="stack gap-small" method="POST" action="set_password_for_real.php">
            <div class="input_group">
                <label for="password">Nytt lösenord</label>
                <input placeholder="Jakob.Ulfsson@student.uu.se" minlength="5" maxlength="40" type="password" name="password" required/>
                <input type="hidden" name="token" value="<?= $token; ?>" />
            </div>

            <input class="btn branding" type="submit" value="Spara lösenord" />
            
        </form> 
    </div>
</body>
</html>