<div class="navbar_container">
    <div class="navbar">
        <?php include "_logo.php" ?>

        <div class="navbar_content">
            <form class="search" action="search.php">
                <div class="search_container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M23.822 20.88l-6.353-6.354c.93-1.465 1.467-3.2 1.467-5.059.001-5.219-4.247-9.467-9.468-9.467s-9.468 4.248-9.468 9.468c0 5.221 4.247 9.469 9.468 9.469 1.768 0 3.421-.487 4.839-1.333l6.396 6.396 3.119-3.12zm-20.294-11.412c0-3.273 2.665-5.938 5.939-5.938 3.275 0 5.94 2.664 5.94 5.938 0 3.275-2.665 5.939-5.94 5.939-3.274 0-5.939-2.664-5.939-5.939z"/></svg>
                    <input placeholder="Sök redidit..." minlength="2" maxlength="100" type="search" name="query" />
                </div>
            </form>
            
        <?php if(isset($_SESSION["user"])): ?>
            <a href="user.php">
                <div class="nav_item">
                    Ditt konto
                </div>  
            </a>
        <?php else: ?>
            <a href="auth/login.php">
                <div class="nav_item">
                    Logga in
                </div>  
            </a>
        <?php endif; ?>
        </div>
    </div>
</div>
