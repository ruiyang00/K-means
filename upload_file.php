<?php
require "header.php"
?>


<main>
<?php

if (isset($_POST['go']) && isset($_SESSION['user_Name'])) {
    require 'server/dbConnect.php';
    $connection = new mysqli($hn, $un, $pw, $db);
    if ($connection->connect_error) {
        die($connection->connect_error);
    }

    $mode_name = get_post($connection, "filename");
    $selected_k_value = get_post($connection, "k_value");
    $selected_action_value = get_post($connection, "action");
    $selected_file_value = get_post($connection, "selectfile");
    $selected_text_value = get_post($connection, "text_input");

    if (empty($mode_name)) {
        echo '<h2>faild to train/test your model</h2>';
        echo '<h3>please go back to K-means page and input the name for your file and resubmit it</h3>';
        exit();
    }

    if (!empty($selected_file_value) && (!empty($selected_text_value))) {
        echo '<h2>faild to train/test your model</h2>';
        echo '<h3>either to input your scores into text box or upload a .text file that contains the scores</h3>';
        exit();
    }

    // if user gets here that means user fullfill the k-means submission form, user has been authenticated, and get here by click the submit button from k-means page

    if ($selected_action_value === "training") {

        if (!empty($selected_text_value)) {
            $k_value = 3;
            $inputs = explode(",", $selected_text_value);
            $index1 = array_rand($inputs);
            $index2 = array_rand($inputs);
            $index3 = array_rand($inputs);

            $centroid = array(
                array(0, 0, 0),
                array($inputs[$index1], $inputs[$index2], $inputs[$index3]),
            );
            $newCentroid = getCentroid($inputs, $k_value, $centroid);
            $comma_separated = implode(",", $newCentroid);

            $check_model_existing = "SELECT user_id FROM uploads WHERE user_id=$_SESSION[user_ID]";

            $model_result = mysqli_query($connection, $check_model_existing);

            $model_rowcount = mysqli_num_rows($model_result);

            if ($model_rowcount > 0) {
                $query = "UPDATE uploads SET filecontents = '$comma_separated' WHERE user_id=$_SESSION[user_ID]";

                $result = $connection->query($query);
                if (!$result) {
                    echo "INSERT failed: $query<br>" . $connection->error . "<br><br>";
                }

            } else {

                $query = "INSERT INTO uploads (user_id,filename, filecontents) VALUES ($_SESSION[user_ID],'$mode_name', '$comma_separated')";

                $result = $connection->query($query);
                if (!$result) {
                    echo "INSERT failed: $query<br>" . $connection->error . "<br><br>";
                }
            }

        } else {
            //upload a file
            $file_content = file_get_contents($selected_file_value);

            $query = "INSERT INTO uploads (user_id,filename, filecontents) VALUES ($_SESSION[user_ID],'$mode_name', '$file_content')";

            $result = $connection->query($query);
            if (!$result) {
                echo "INSERT failed: $query<br>" . $connection->error . "<br><br>";
            }

        }

        echo '<h2>
        Sucessfully trained our model
        </h2>';

    } elseif ($selected_action_value === "testing") {

        $check_model_existing = "SELECT user_id FROM uploads WHERE user_id= $_SESSION[user_ID]";

        $model_result = mysqli_query($connection, $check_model_existing);

        $model_rowcount = mysqli_num_rows($model_result);

        if ($model_rowcount > 0) {

            $query = "SELECT filecontents FROM uploads WHERE user_id= $_SESSION[user_ID]";

            $result = $connection->query($query);
            if (!$result) {
                echo "SELECT failed: $query<br>" . $connection->error . "<br><br>";
            }
            $rows = $result->fetch_assoc();
            $k_value = 3;
            $dataPar = explode(",", $rows['filecontents']);
            $inputCent[0] = array(0, 0, 0);
            $inputCent[1] = $dataPar;

            if (!empty($selected_text_value)) {

                $inputs = explode(",", $selected_text_value);
                $finalCluster = getfinalCluster($inputs, $k_value, $inputCent);

                echo '<h2>
            Your clusters are:
           </h2>';
                print_r($finalCluster);

            } elseif (!empty($selected_file_value)) {
                $file_content = file_get_contents($selected_file_value);
                $inputs = explode(",", $selected_file_value);
                $finalCluster = getfinalCluster($inputs, $k_value, $inputCent);

            }

        } else {

            echo "You do not have a existing model, please go back to K-means page create and train a model";

        }

    } else {
        //upload a file
        $file_content = file_get_contents($selected_file_value);

        $query = "INSERT INTO uploads (user_id,filename, filecontents) VALUES ($_SESSION[user_ID],'$mode_name', '$file_content')";

        $result = $connection->query($query);
        if (!$result) {
            echo "INSERT failed: $query<br>" . $connection->error . "<br><br>";
        }
    }

    // echo "Your file name:$_SESSION[user_Name]";
    // echo "Your file name:$_SESSION[user_ID]";
    // echo "Your file name:$mode_name";
    // echo "Your k: $selected_k_value";
    // echo "Your action:$selected_action_value";
    // echo "Your input text:$selected_text_value";
    $result->close();

    $connection->close();

} elseif (isset($_SESSION['user_Name']) && !isset($_POST['go'])) {

    echo 'please go to your upload page and submit our input to your model please go back to K-means to submit your inputs!!!!';
}

function getCentroid($data, $numcluster, $centroid)
{
    $distance = array();
    $cluster = array();
    $clusternode = array();

    $centroid[0] = $centroid[1];
    $centroid[1] = array(0, 0, 0);
    $newCentroid = array();

    for ($i = 0; $i < $numcluster; $i++) {
        for ($j = 0; $j < sizeof($data); $j++) {
            $distance[$i][$j] = abs($data[$j] - $centroid[0][$i]);
        }
    }

    for ($z = 0; $z < sizeof($data); $z++) {
        $minDistance = 0;
        if ($distance[0][$z] < $distance[1][$z] && $distance[0][$z] < $distance[2][$z]) {
            $minDistance = 0;
        }
        if ($distance[1][$z] < $distance[0][$z] && $distance[1][$z] < $distance[2][$z]) {
            $minDistance = 1;
        }
        if ($distance[2][$z] < $distance[0][$z] && $distance[2][$z] < $distance[1][$z]) {
            $minDistance = 2;
        }
        $centroid[1][$minDistance] = $centroid[1][$minDistance] + $data[$z];
        $clusternode[$minDistance] = $clusternode[$minDistance] + 1;
        $cluster[$z] = $minDistance;
    }

    // print_r($distance);
    // print_r($cluster);
    // print_r($centroid[1]);
    // print_r($clusternode);

    for ($i = 0; $i < $numcluster; $i++) {
        $centroid[1][$i] = intval($centroid[1][$i] / $clusternode[$i]);
    }

    // print_r($centroid[1]);
    $isAchived = 1;

    for ($i = 0; $i < $numcluster; $i++) {
        if ($centroid[0][$i] != $centroid[1][$i]) {
            $isAchived = 0;
        }
    }

    // print_r($centroid);

    // echo "The value of achived is: $isAchived";
    // print_r($centroid[0]);
    // print_r($centroid[1]);
    // echo "-------------------call----------\n";

    if ($isAchived == 0) {
        return getCentroid($data, $numcluster, $centroid);
    } else {
        // print_r($centroid);

        return $centroid[1];
    }

    // return $centroid;
} //end of getCentroid();

function getfinalCluster($data, $numcluster, $centroid)
{
    $distance = array();
    $cluster = array();
    $clusternode = array();

    $centroid[0] = $centroid[1];
    $centroid[1] = array(0, 0, 0);
    $finalCluseter = array();

    for ($i = 0; $i < $numcluster; $i++) {
        for ($j = 0; $j < sizeof($data); $j++) {
            $distance[$i][$j] = abs($data[$j] - $centroid[0][$i]);
        }
    }

    for ($z = 0; $z < sizeof($data); $z++) {
        $minDistance = 0;
        if ($distance[0][$z] < $distance[1][$z] && $distance[0][$z] < $distance[2][$z]) {
            $minDistance = 0;
        }
        if ($distance[1][$z] < $distance[0][$z] && $distance[1][$z] < $distance[2][$z]) {
            $minDistance = 1;
        }
        if ($distance[2][$z] < $distance[0][$z] && $distance[2][$z] < $distance[1][$z]) {
            $minDistance = 2;
        }
        $centroid[1][$minDistance] = $centroid[1][$minDistance] + $data[$z];
        $clusternode[$minDistance] = $clusternode[$minDistance] + 1;
        $cluster[$z] = $minDistance;
    }

    // print_r($distance);
    // print_r($cluster);
    // print_r($centroid[1]);
    // print_r($clusternode);

    for ($i = 0; $i < $numcluster; $i++) {
        $centroid[1][$i] = intval($centroid[1][$i] / $clusternode[$i]);
    }

    // print_r($centroid[1]);
    $isAchived = 1;

    for ($i = 0; $i < $numcluster; $i++) {
        if ($centroid[0][$i] != $centroid[1][$i]) {
            $isAchived = 0;
        }
    }

    // print_r($centroid);

    // echo "The value of achived is: $isAchived";
    // print_r($centroid[0]);
    // print_r($centroid[1]);
    // echo "-------------------call----------\n";
    if ($isAchived == 1) {
        // print_r($centroid);
        for ($i = 0; $i < $numcluster; $i++) {
            for ($j = 0; $j < sizeof($data); $j++) {
                if ($cluster[$j] == $i) {
                    $finalCluseter[$i][$j] = $data[$j];
                }
            }
        }
    }
    if ($isAchived == 0) {
        return getCentroid($data, $numcluster, $centroid);
    } else {

        return $finalCluseter;

    }

    // return $centroid;
    // return $centroid;
} //end of getCentroid();

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

?>


</main>

