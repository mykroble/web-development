<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "lobster_Inventory";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if ($conn) {
    } else {
        echo "not connecting";
    }

?>