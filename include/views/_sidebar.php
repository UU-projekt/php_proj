<?php
$newThreads = getNewThreads();
$savedThreads = [];

if(isset($_SESSION["user"])) {
    $savedThreads = getStarredThreads($_SESSION["user"]["id"]);
}
 
?>

<div class="sidebar"> 
    <script type="module" src="/js/sidebar.js" defer></script>

    <?php if(isset($_SESSION["user"]) && basename($_SERVER['PHP_SELF']) != "submit.php"): ?>
        <div class="section stack gap-large">
            <ul class="stack gap-large">
                <li class="newThread">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 9h-9v-9h-6v9h-9v6h9v9h6v-9h9z"/></svg>
                    <a href="/submit.php">Ny Tråd</a>
                </li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(!empty($ft)): ?>

    <?php endif; ?>

    <div class="section stack gap-large">
        <h3 class="fredoka-500 label">Nya trådar</h3>
        <ul class="stack gap-large">
            <?php foreach($newThreads as $nt): ?>
                <li>
                    <span class="tag error">new</span>
                    <a rel="prefetch" href="/thread.php?id=<?= $nt["id"] ?>"><?= strip_tags($nt["title"]) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if(isset($_SESSION["user"])): ?>
        <div class="section stack gap-large">
            <h3 class="fredoka-500 label">Sparade Trådar</h3>
            <ul class="stack gap-large" id="savedThread">
            <?php foreach($savedThreads as $st): ?>
                <li data-starred-id="<?= $t["id"] ?>">
                    <button onclick="removeStar('<?= $t["id"] ?>', this)" class="btn tetriary star starred">
                        <svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/></svg>
                    </button>
                    <a rel="prefetch" href="/thread.php?id=<?= $st["threadID"] ?>"><?= strip_tags($st["title"]) ?></a>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>

        <hr>
    <?php endif; ?>

        <div class="section stack gap-large">
            <h3 class="fredoka-500 label">Information</h3>
            <ul class="stack gap-large">
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12.014 6.54s2.147-3.969 3.475-6.54l8.511 8.511c-2.583 1.321-6.556 3.459-6.556 3.459l-5.43-5.43zm-8.517 6.423s-1.339 5.254-3.497 8.604l.827.826 3.967-3.967c.348-.348.569-.801.629-1.288.034-.27.153-.532.361-.74.498-.498 1.306-.498 1.803 0 .498.499.498 1.305 0 1.803-.208.209-.469.328-.74.361-.488.061-.94.281-1.288.63l-3.967 3.968.826.84c3.314-2.133 8.604-3.511 8.604-3.511l4.262-7.837-3.951-3.951-7.836 4.262z"/></svg>
                    <a href="/info/markdown.php">Markdown</a>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    <a href="https://github.com/UU-projekt/php_proj">GitHub</a>
                </li>
            </ul>
        </div>

        <hr>
</div>