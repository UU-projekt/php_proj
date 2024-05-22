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

    $isStarred = false;
    if(isset($_SESSION["user"])) {
        $isStarred = getStarredThread($_SESSION["user"]["id"], $_GET["id"]);
    }
    
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?= strip_tags($t["title"]) ?> - Redidit</title>
		<link rel="stylesheet" href="./css/index.css">
        <link rel="stylesheet" href="./css/thread.css">
        <script src="/js/comments.js" type="module" defer></script>
	</head>
	<body class="roboto-regular stack center">
		<?php include "./include/views/_navbar.php" ?>
		
        <script>
            // Vi vill ha åtkomst till trådens data i js runtimen. Som tur är så är JSON ett giltigt sätt att deklarera js variabler.
            // (kanske inte jättekonstigt med tanke på att det konceptet namngav JSON)
            const threadDetails = <?= json_encode($t) ?>

            <?php if(isset($_SESSION["user"])): ?>
            const currentUser   = <?= json_encode($_SESSION["user"]) ?>
            <?php endif; ?>
        </script>

        <div class="layout">
            <?php include "./include/views/_sidebar.php" ?>
            <div class="content stack gap-huge">
                <div class="thread-details stack gap-medium">
                    <h1 class="fredoka-700 fsize-2em"><?= strip_tags($t["title"]) ?></h1>
                    <div class="thread-meta"> 
                        <p>postades <span title="<?= date("d/m/y H:i", $t["created"]) ?>"><?= date("d/m/y", $t["created"]) ?></span> av <b><?= strip_tags($t["username"]) ?></b>
                        </p>
                    </div>
                    <article>
                        <section class="md">
                            <div class="starrow">

                                <?php if(isset($_SESSION["user"]) && $t["author"] == $_SESSION["user"]["id"]): ?>
                                <button onclick="deleteThread()" id="deletebtn" class="btn tetriary">
                                    <svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m20.015 6.506h-16v14.423c0 .591.448 1.071 1 1.071h14c.552 0 1-.48 1-1.071 0-3.905 0-14.423 0-14.423zm-5.75 2.494c.414 0 .75.336.75.75v8.5c0 .414-.336.75-.75.75s-.75-.336-.75-.75v-8.5c0-.414.336-.75.75-.75zm-4.5 0c.414 0 .75.336.75.75v8.5c0 .414-.336.75-.75.75s-.75-.336-.75-.75v-8.5c0-.414.336-.75.75-.75zm-.75-5v-1c0-.535.474-1 1-1h4c.526 0 1 .465 1 1v1h5.254c.412 0 .746.335.746.747s-.334.747-.746.747h-16.507c-.413 0-.747-.335-.747-.747s.334-.747.747-.747zm4.5 0v-.5h-3v.5z" fill-rule="nonzero"/></svg>
                                </button>

                                <a id="editbtn" class="btn tetriary" href="/submit.php?edit=<?= $t["id"] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 13.616v-3.232c-1.651-.587-2.694-.752-3.219-2.019v-.001c-.527-1.271.1-2.134.847-3.707l-2.285-2.285c-1.561.742-2.433 1.375-3.707.847h-.001c-1.269-.526-1.435-1.576-2.019-3.219h-3.232c-.582 1.635-.749 2.692-2.019 3.219h-.001c-1.271.528-2.132-.098-3.707-.847l-2.285 2.285c.745 1.568 1.375 2.434.847 3.707-.527 1.271-1.584 1.438-3.219 2.02v3.232c1.632.58 2.692.749 3.219 2.019.53 1.282-.114 2.166-.847 3.707l2.285 2.286c1.562-.743 2.434-1.375 3.707-.847h.001c1.27.526 1.436 1.579 2.019 3.219h3.232c.582-1.636.75-2.69 2.027-3.222h.001c1.262-.524 2.12.101 3.698.851l2.285-2.286c-.744-1.563-1.375-2.433-.848-3.706.527-1.271 1.588-1.44 3.221-2.021zm-12 2.384c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4z"/></svg>
                                </a>
                                <?php endif; ?>

                                <?php if(isset($_SESSION["user"])): ?>
                                    <?php if($isStarred): ?>
                                        <button onclick="removeStar('<?= $t["id"] ?>', this)" class="btn tetriary star starred">
                                    <?php else: ?>
                                        <button onclick="addStar('<?= $t["id"] ?>', this)" class="btn tetriary star">
                                    <?php endif; ?>
                                        <svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/></svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?= $t["html"] ?>
                        </section>
                        
                        <?php if(isset($t["imageid"])): ?>
                        <div class="image-container stack gap-huge">
                            <img src="/cdn/image.php?key=<?= $t["imageid"] ?>" />
                        </div>
                        <?php endif; ?>
                    </article>
                </div>

                
                <?php if(!isset($_SESSION["user"])): ?> 
                    <p>Du är inte inloggad. <a href="/auth/login.php?url=/thread.php?id=<?= $t["id"] ?>">Logga in</a> för att besvara tråden!</p>
                <?php else: ?>
                    <div id="main_comment" class="commentInput" class="commentbox"></div>
                <?php endif; ?>
                
                <div id="<?= $t["id"] ?>" class="stack gap-medium">
                    
                </div>
                
            </div>
        </div>
	</body>
</html>
