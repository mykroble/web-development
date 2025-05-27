<?php
include('connect.php');
if (isset($_GET['deleteid'])) {
    $id=$_GET['deleteid'];
    echo $id;
    $sql="DELETE FROM `patient` WHERE id=$id";
    $result=mysqli_query($conn,$sql);
    if ($result) {
        header('location:patientList.php');
    } else {
        echo "bruh";
    }
}
?>