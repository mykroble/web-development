
<!DOCTYPE html>
<html>
    <head> 
        <title>Posts</title>  
    </head>
    <body>
    <ul>
        <?php
            foreach($tasks as $list){
                echo "<h1><li> $list </li></h1>";
            }
        ?>
    </ul>
    
    </body>
    <form method="POST" action="">
Add a task <input type="text" name="task"><br>
<input type="submit">
</form>
</html>