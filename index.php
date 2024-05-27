<?php
	include "./include/bootstrap.php";

	$highStar = getHighStarThreads();
	$highComment = getHighCommentThreads();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>som reddit fast bättre - Redidit</title>
		<link rel="stylesheet" href="./css/index.css">
	</head>
	<body class="roboto-regular stack containerthing">
		<?php include "./include/views/_navbar.php" ?>
		<div class="layout">
            <?php include "./include/views/_sidebar.php" ?>

            <div class="content stack gap-huge">
                
			<div class="thread_list">
				<svg class="starxd" width="24" height="24" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/></svg>
				<h1>Högt gillade trådar</h1>
				<div class="stack gap-large">
					<?php foreach($highStar as $r): ?>
						<?= renderResult($r) ?>
					<?php endforeach ?>
				</div>
			</div>

			<div class="thread_list">
				<svg class="fire" width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M8.625 0c.61 7.189-5.625 9.664-5.625 15.996 0 4.301 3.069 7.972 9 8.004 5.931.032 9-4.414 9-8.956 0-4.141-2.062-8.046-5.952-10.474.924 2.607-.306 4.988-1.501 5.808.07-3.337-1.125-8.289-4.922-10.378zm4.711 13c3.755 3.989 1.449 9-1.567 9-1.835 0-2.779-1.265-2.769-2.577.019-2.433 2.737-2.435 4.336-6.423z"/></svg>
				<h1>Högt kommenterade trådar</h1>
				<div class="stack gap-large">
					<?php foreach($highComment as $r): ?>
						<?= renderResult($r) ?>
					<?php endforeach ?>
				</div>
			</div>

            </div>
        </div>
	</body>
</html>
