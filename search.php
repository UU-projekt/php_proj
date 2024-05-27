<?php
	include "./include/bootstrap.php";
    if(!isset($_GET["query"])) {
        header("location: ../index.php");
        die();
    }
    $res = searchThread($_GET["query"]);

    //echo var_dump($res);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sök - Redidit</title>
		<link rel="stylesheet" href="css/index.css">
	</head>
	<body class="roboto-regular stack containerthing">
		<?php include "./include/views/_navbar.php" ?>
		<div class="layout">
            <?php include "./include/views/_sidebar.php" ?>

            <div class="content">
                <h1>Resultat</h1>

                <div class="stack gap-large">
                    <?php foreach($res as $r): ?>
                        <?= renderResult($r) ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
	</body>
</html>
