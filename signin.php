<?php
require "header.php"
?>


<main>
    <div class="card">
        <div class="container">
            <form action="./server/authenticateSignin.php" method="post">
                <h2>
                    enter your existing account here
                </h2>
                Enter your email here  <input type="text" name="email" placeholder="your@email.com"><br><br>
                Enter your password here  <input type="text" name="password" placeholder="Your paswword"><br><br>
                <button type="submit" value="signup_submit" name="signup_submit_button">Sign In </button>
            </form>
        </div>
    </div>
</main>

