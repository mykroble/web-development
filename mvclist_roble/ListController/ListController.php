<?php
include 'ListModel/ListModel.php';
    if (isset($_POST['task'])) {
        $addthis = $_POST['task'];
        addTasks($addthis);
    }
        else {
        $_SESSION['list'] = []; 
    }

$tasks = getTasks();
require 'ListView/ListView.php';
?>