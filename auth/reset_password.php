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

        <form class="stack gap-small" method="POST" action="request_psw_reset.php">
            <div class="input_group">
                <label for="email">Email</label>
                <input value="<?= strip_tags($_GET["email"]); ?>" placeholder="Jakob.Ulfsson@student.uu.se" type="email" name="email" required/>
            </div>

            <input class="btn branding" type="submit" value="Återställ lösenord" />
            
        </form> 
    </div>
</body>
</html>