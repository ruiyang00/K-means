<?php
require "header.php"
?>


<main>

    <?php
if (isset($_SESSION['user_Name'])) {
    echo '<p class = "login-status"> You are logging!</p>';
} else {

    echo '<p class = "login-status"> Welcome to our Clustering-based unsupervised Homepage! </p>';
}
?>
</main>
