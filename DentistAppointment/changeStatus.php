<?php
include('connect.php');
if (isset($_GET['scheduleid'])) {
    $newStatus=$_GET['statusChange'];
    $id=$_GET['scheduleid'];
    echo $id;
    $sql="UPDATE schedule SET `status` = '$newStatus' WHERE id = $id";
    $result=mysqli_query($conn,$sql);
    if ($result) {
        header('location:schedule.php');
    } else {
        echo "bruh";
    }
}
?>