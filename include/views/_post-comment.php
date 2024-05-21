<div class="commentbox">
<?php if(isset($_SESSION["user"])): ?>

<?php else: ?>
    <p>Du måste <a href="/auth/login.php">logga in</a> för att skapa kommentarer</p>
<?php endif; ?>
</div>
