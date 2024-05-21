<?php
 $newThreads = getNewThreads();
?>

<div class="sidebar"> 

    <?php if(isset($_SESSION["user"]) && basename($_SERVER['PHP_SELF']) != "submit.php"): ?>
        <div class="section stack gap-large">
            <ul class="stack gap-large">
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 9h-9v-9h-6v9h-9v6h9v9h6v-9h9z"/></svg>
                    <a href="/submit.php">Ny Tråd</a>
                </li>
            </ul>
        </div>
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
        <div class="section">
            <h3 class="fredoka-500 label">Dina trådar</h3>
        </div>

        <hr>
    <?php endif; ?>

        <div class="section">
            <h3 class="fredoka-500 label">Aktiva Trådar</h3>
            <ul>
                
            </ul>
        </div>

        <hr>
</div>