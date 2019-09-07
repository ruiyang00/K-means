<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <nav>
            <ul class="topnav">
            <li><a href="index.php">CS 174 Final Project</a></li>

            <?php
if (isset($_SESSION['user_Name'])) {

    echo '
    <li><a href="upload_page.php">K-means</a></li>
        <li class="topnav-right"><a href="./server/authenticateLogout.php"> Log Out</a></li>
    ';
} else {

    echo '
                    <li class="topnav-right"><a href="signup.php"> Sign Up</a></li>
                    <li class="topnav-right"><a href="signin.php"> Sign In</a></li>';
}
?>


            </ul>
        </nav>
    </header>

</body>

</html>
