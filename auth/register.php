<?php include "../include/bootstrap.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <?php require "../include/views/_head.php" ?>
    <link rel="stylesheet" href="../css/register/index.css">
</head>
<body class="roboto-regular center">    
    <div id="logo"></div>
    <div id="content" class="stack gap-large">
        <?php include "../include/views/_logo.php"; ?>

        <?php include "../include/views/_info_bar.php"; ?>

        <form class="stack gap-small" method="POST" action="create_user.php">
            <div class="input_group">
                <label for="email">Email</label>
                <input placeholder="Jakob.Ulfsson@student.uu.se" type="email" name="email" required/>
            </div>

            <div class="input_group">
                <label for="username">Användarnamn</label>
                <input placeholder="xX_Jakob_Ulfsson_Xx" type="text" minlength="3" maxlength="40" name="username" required/>
            </div>

            <div class="input_group">
                <label for="password">Lösenord</label>
                <input placeholder="Lösenord" type="password" minlength="5" maxlength="40" name="password" required/>
            </div>

            <input class="btn branding" type="submit" value="skapa konto" />
        </form> 
        <small>Genom att skapa ett konto godkänner du våra <a href="/legal/terms.php">villkor</a>.</small>
    </div>
</body>
</html>