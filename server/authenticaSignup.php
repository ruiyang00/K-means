<?php

if (isset($_POST['signup_submit_button'])) {
    require 'dbConnect.php';
    $connection = new mysqli($hn, $un, $pw, $db);
    if ($connection->connect_error) {
        die($connection->connect_error);
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repated_password = $_POST['repated_password'];

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../signup.php?error=emptyfiedls&username=" . $username . "&email=" . $email);
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && (!preg_match("/^[a-zA-Z0-9_\\-]*$/", $username))) {
        header("Location: ../signup.php?error=invalidmail&username");
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../signup.php?error=invalidatemail&username=" . $username);
        exit();
    } elseif (!preg_match("/^[a-zA-Z0-9_\\-]*$/", $username)) {
        header("Location: ../signup.php?error=invalidateusername&email=" . $email);
        exit();
    } elseif ($password !== $repated_password) {
        header("Location: ../signup.php?error=passwordsnotthesame&email=" . $email . "&username=" . $username);
        exit();
    } else {

        $sql_email = get_post($connection, "email");
        $sql_username = get_post($connection, "username");
        $sql_password = get_post($connection, "password");


        $select_email = "SELECT email FROM users WHERE email ='$sql_email' ";

        $email_result = mysqli_query($connection, $select_email);

        $email_rowcount = mysqli_num_rows($email_result);

        

        

        $select_username = "SELECT username FROM users WHERE username ='$sql_username'";

        $username_result = mysqli_query($connection, $select_username);
        $username_rowcount = mysqli_num_rows($username_result);

        

        // echo <<<_END
        // <h1> $email_rowcount</h1>
        // <h2>$username_rowcount</h2>
            
        // _END;
        
        

        if ($email_rowcount > 0) {
            header("Location: ../signup.php?error=emailtaken&username=" . $sql_username);
            exit();


        } elseif ($username_rowcount > 0) {
            header("Location: ../signup.php?error=usernametaken&mail=" . $sql_email);
            exit();


        } else {

            $salt1 = "qm&h";
            $salt2 = "pg!@";

            $token = hash('ripemd128', "$salt1$sql_password$salt2");

            create_user($connection, $sql_email, $sql_username, $token);

            header("Location: ../signup.php?signup=success");
            exit();
        }

        // }

        // mysqli_stmt_close($stmt);

    }

    $connection->close();

} else {
    header("Location: ../signup.php?signup=success");
    exit();
}

function get_post($connection, $var)
{
    return $connection->real_escape_string($_POST[$var]);
}
function create_user($connection, $em, $un, $pw)
{

    $query = "INSERT INTO users (email, username, password) VALUES ('$em', '$un', '$pw')";
    $result = $connection->query($query);

    if (!$result) {
        echo "INSERT failed: $query<br>" . $connection->error . "<br><br>";
    }
}
