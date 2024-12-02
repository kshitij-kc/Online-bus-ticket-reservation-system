<?php
// Start session if not already started in other files
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <ul>
        <li class="logo"><h4>Online</h4></li>
        <li class="btn"><span class="fas fa-bars"></span></li>
        <div class="items">
            <li><a href="home.php">Home</a></li>

            <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="logout.php">Logout</a></li>';
					echo '<li><a href="recommendation.php">Custom Recommendations</a></li>';
                } else {
                    echo '<li><a href="loginMenu.php">Login</a></li>';
                }
            ?>

            <li><a href="services.php">Services</a></li>
            <li><a href="contact_us.php">Contact Us</a></li>
        </div>
        <li class="search-icon">
            <input type="search" placeholder="Search">
            <label class="icon">
                <span class="fas fa-search"></span>
            </label>
        </li>
    </ul>
</nav>

<script>
    $('nav ul li.btn span').click(function(){
        $('nav ul div.items').toggleClass("show");
        $('nav ul li.btn span').toggleClass("show");
    });
</script>
