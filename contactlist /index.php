
<?php
include("database.php");

// Handle form submission
if (isset($_POST["submit"])) {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $num = $_POST["num"];

    // Input validation
    if (empty($fname) || empty($lname) || empty($email) || empty($num)) {
        echo "All fields are required";
    } else {
        $sql = "INSERT INTO `list` (`First_Name`, `Last_Name`, `Email`, `Phone_Number`) 
                VALUES ('$fname', '$lname', '$email', '$num')";
        mysqli_query($conn, $sql);
        header('Location: index.php');
        exit;
    }
}

// Fetch data from database
$sql = "SELECT * FROM list";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contacts</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<button class="open-button" onclick="openForm()">Create Contact</button>
<h1>Contact List</h1>
<div class="form-popup" id="myForm">
  <form action="index.php" class="form-container" method="POST">
    <h1>CONTACT</h1>
    <label for="fname"><b>First Name</b></label>
    <input type="text" placeholder="Enter first name" name="fname" required>
    
    <label for="lname"><b>Last Name</b></label>
    <input type="text" placeholder="Enter last name" name="lname" required>
    
    <label for="email"><b>Email</b></label>
    <input type="email" placeholder="Enter email" name="email" required>

    <label for="number"><b>Phone Number</b></label>
    <input type="number" placeholder="Enter phone number" name="num" required>

    <input type="submit" class="btn" name="submit" value="Save">
    <button type="button" class="btn cancel" onclick="closeForm()">Cancel</button>
  </form>
</div>

<table>
  <tr>
    <th>ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Email</th>
    <th>Phone Number</th>
    <th>Actions</th>
  </tr>
  <?php
  if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $id = $row['ID'];
      $fname = $row['First_Name'];
      $lname = $row['Last_Name'];
      $email = $row['Email'];
      $num = $row['Phone_Number'];
        echo ' <tr>
        <th scope="row">' .$id.'</th>
        <td>'.$fname.'</td>
        <td>'.$lname.'</td>
        <td>'.$email.'</td>
        <td>'.$num.'</td>
        <td>
        <button class="button"><a href="delete.php? deleteid='.$id.'">DELETE</a></button>
        <button class="button"><a href="update.php? updateid='.$id.'">UPDATE</a></button>
        </td>
        </tr>';
        

    }
  } else {
      echo "<tr><td colspan='6'>No contacts found.</td></tr>";
  }
  ?>
</table>

<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>

</body>
</html>

<?php
mysqli_close($conn);
?>
