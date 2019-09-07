<?php
function getCentroid($data, $numcluster, $centroid)
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

    echo $isAchived;
    if ($isAchived == 0) {
        return getCentroid($data, $numcluster, $centroid);
    } else {

        return $finalCluseter;

    }

    // return $centroid;
    // return $centroid;
} //end of getCentroid();

$arr1 = array(2, 4, -10, 12, 3, 20, 30, 11);
$k_value = 3;
$centro = array(
    array(0, 0, 0),
    array(2, 4, 30),
);
$option = "testing";
// $comma_separated = implode(",", $arr1);
// echo $comma_separated;
// print_r($centro);

// getCentroid($data, $numcluster, $centroid);

$arra2 = getCentroid($arr1, $k_value, $centro);
print_r($arra2);

// print_r($arra2);
