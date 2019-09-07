<?php
require "header.php"
?>


<main>
    <div class="card">
        <div class="container">
            <form action="./server/authenticaSignup.php" method="post">
                <h2>
                    a\babadq your new account
                </h2>
                Enter your email here  <input type="text" name="email" placeholder="your@email.com"><br><br>
                Enter your username here  <input type="text" name="username" placeholder="Your Username"><br><br>
                Enter your password here  <input type="text" name="password" placeholder="Your paswword"><br><br>
                Repteat your password here  <input type="text" name="repated_password" placeholder="Your paswword"><br><br>
                <input type="hidden" name="submit" value="yes"> <br><br>
                <button type="submit" value="signup_submit" name="signup_submit_button">Sign Up </button>
            </form>
        </div>
    </div>
</main>

