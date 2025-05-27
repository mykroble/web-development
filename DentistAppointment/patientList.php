<?php
    include('connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>

        <!-- copy these -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<!-- Entire Container -->
<div style="display: flex; background-color:lightgray;">

<!-- container for sidebar fixed-->
<div class="d-flex flex-column flex-shrink-0 p-3 text-dark bg-light" style="width: 140px; height:100vh; position:fixed;"> 
<ul class="nav flex-column" style="margin-top: 140px;">
  <li class="nav-item">
    <a class="nav-link" aria-current="page" href="schedule.php"><img src="calendar_month_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="#" width="70px"></a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="patientList.php"><img src="id_card_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="" width="70px"></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="createPatient.php"><img src="person_add_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="" width="70px"></a>
  </li>
</ul></div>
<!-- ghost sidebar -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 140px; height:100vh;">ghost</div> 

<!-- container for content -->
<div class="container-fluid" style="background-color: lightgray;"> 
<h1 style="padding: 50px;">Patient Records</h1>

<!-- container for table -->
<div class="container-fluid shadow-lg rounded" style="background-color:white;padding:50px"> 
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Gender</th>
      <th scope="col">Birthday</th>
      <th scope="col">Phone Number</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>

<?php
    $sql="SELECT * FROM `patient`";
    $result=mysqli_query($conn,$sql);
    if ($result) {
        while ($row=mysqli_fetch_assoc(($result))) {
            $id=$row['id'];
            $MI = mb_substr($row['mname'], 0, 1);
            echo '<tr>
            <th scope="row">'.$id.'</th>
            <td><a href="patientProfile.php?patientid='.$id.'">'.$row['fname'].' '.$MI.'. '.$row['lname'].'</td>
            <td>'.$row['gender'].'</td>
            <td>'.$row['birthdate'].'</td> 
            <td>'.$row['phone_number'].'</td> 
            <td>
            <button type="button" class="btn btn-info"><a href="editPatient.php?updateid='.$id.'" style="text-decoration:none;color:black;">Edit</a></button>
            <button type="button" class="btn btn-danger"><a href="deletePatient.php?deleteid='.$id.'" style="text-decoration:none;color:black;">Delete</a></button>
            </td>
                </tr>';
        }
    }
    // https://stackoverflow.com/questions/39954084/how-can-i-split-datetime-into-different-parts
?>
    
  </tbody>
</table>

</div><!-- end of table container -->
</div><!-- end of content container -->
</div><!-- end of entire container -->

</body>
</html>