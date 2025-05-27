<?php
include 'database.php';

if (!isset($_GET['updateid'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['updateid'];


$sql = "SELECT * FROM `list` WHERE ID=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$fname = $row['First_Name'];
$lname = $row['Last_Name'];
$email = $row['Email'];
$num = $row['Phone_Number'];


if (isset($_POST['submit'])) {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $num = $_POST["num"];

    $sql = "UPDATE `list` SET First_Name='$fname', Last_Name='$lname', Email='$email', Phone_Number='$num' WHERE ID=$id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header('Location: index.php');
        exit();
    } else {
        die(mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <form method="post" action="">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control" name="fname" value="<?php echo $fname; ?>">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control" name="lname" value="<?php echo $lname; ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="number" class="form-control" name="num" value="<?php echo $num; ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Update</button>
    </form>
</div>
</body>
</html>
