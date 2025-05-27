<?php
 include 'database.php';
 if(isset($_GET['deleteid'])){
    $id = $_GET['deleteid'];
    $sql = "delete from `list` where id=$id";
    $result = mysqli_query($conn, $sql);
    header('Location: index.php');
 }
?>