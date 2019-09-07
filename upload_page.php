<?php
require "header.php"
?>


<main>
<?php

if (isset($_SESSION['user_Name'])) {
    echo '<div class="card">
    <div class="container">
        <form action="upload_file.php" method="post">
        <h2>
                a-means your data
            </h2>
        Name Your Model <input type="text" name="filename"><br><br>

        Your K value will be: <select name="k_value">
            <option value="3">3</option>
        </select><br><br>

        Select your action: <select name="action">
            <option value="training">training</option>
            <option value="testing">testing</option>
        </select><br><br>
        Select File: <input type="file" accept="text" name="selectfile"><br><br>
        Or manually input your scores seprate by commas<br>
        For exmaple: 20,30,40<br><br>
        <textarea rows="4" cols="50" name="text_input"></textarea>
        <br><br>
        <button type="submit" name="go" >SUBMIT</button>
        </form>
        </div>
    </div>';

    // if (isset($_POST['go'])) {
    //     echo '<div class="alert">
    //     <span class="closebtn" onclick="this.parentElement.style.display="none";">&times;</span>
    //     This is an alert box.
    //   </div>';

    // } else {

    //     echo 'submit does not work';
    // }

} else {

    echo '<p class = "login-status"> Plaese sign in first </p>';
}
?>


</main>
