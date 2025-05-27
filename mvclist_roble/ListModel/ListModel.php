<?php

     function addTasks($addedtask){
        $_SESSION['list'][] = $addedtask;
    }

     function getTasks(){
        
        return $_SESSION['list'];
    }

?>