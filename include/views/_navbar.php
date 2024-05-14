<div class="navbar_container">
    <div class="navbar">
        <?php include "_logo.php" ?>

        <div class="navbar_content">
            
        <?php if($_SESSION["user"]): ?>
            <a href="/user.php">
                <div class="nav_item">
                    Ditt konto
                </div>  
            </a>
        <?php else: ?>
            <a href="/auth/login.php">
                <div class="nav_item">
                    Logga in
                </div>  
            </a>
        <?php endif; ?>
        </div>
    </div>
</div>