<?php

if (isset($_POST['signup_submit_button'])) {
    require 'dbConnect.php';
    $connection = new mysqli($hn, $un, $pw, $db);
    if ($connection->connect_error) {
        die($connection->connect_error);
    }

    $email = get_post($connection, "email");
    $password = get_post($connection, "password");

    if (empty($password) || empty($email)) {

        header("Location: ../signin.php");
        exit();
    } else {
        $query = "SELECT * FROM users WHERE email ='$email' ";
        $email_result = mysqli_query($connection, $query);
        $email_rowcount = mysqli_num_rows($email_result);

        if ($email_rowcount > 0) {
            $row = mysqli_fetch_assoc($email_result);
            // $h1 =$row['password'];
            // $h2 = $row['email'];

            // $salt1 = "qm&h";
            // $salt2 = "pg!@";

            // $token = hash('ripemd128', "$salt1$password$salt2");

            // if($row['password']===$token){
            //     echo <<<_END
            //     <h1>true </h1>
            //     _END;
            // }else{
            //     echo <<<_END
            //     <h1>false </h1>
            //     _END;
            // }

            if (pwdCheck($password, $row['password'])) {
                session_start();
                $_SESSION[user_ID] = $row['id'];
                $_SESSION[user_Name] = $row['username'];

                header("Location: ../index.php?login=sucess");
                exit();

            } else {

                header("Location: ../signin.php?login=error=wrongpassword=");
                exit();
            }
        } else {
            header("Location: ../signin.php?login=error= nonexistingemail=" . $email);
            exit();
        }
    }

} else {
    header("Location: ../signin.php");
    exit();
}

function get_post($connection, $var)
{
    return $connection->real_escape_string($_POST[$var]);
}

function pwdCheck($input, $dbhashpsd)
{
    $salt1 = "qm&h";
    $salt2 = "pg!@";

    $token = hash('ripemd128', "$salt1$input$salt2");

    if ($token === $dbhashpsd) {
        return true;
    } else {
        return false;
    }
}
