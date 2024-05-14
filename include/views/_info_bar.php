<?php 
include "../bootstrap.php";
?>

<?php if(isset($_SESSION["error"])): ?>
    
    <div class="info_bar code_<?= substr($_SESSION["error"]["code"], 0, 1) ?>xx">
        <p class="code"><?= $_SESSION["error"]["code"]; ?></p>
        <div class="stack gap-medium">
            <h4 class="message fredoka-600"><?= $_SESSION["error"]["message"]; ?></h4>
            <p><?= $_SESSION["error"]["detailed"] ?></p>
        </div>
    </div>
    
<?php endif; ?>


<?php
    if(isset($_SESSION["error"])) {
        unset($_SESSION["error"]);
    }
?>