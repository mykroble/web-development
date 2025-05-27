<?php
    include('connect.php');
    $patientid=$_GET['patientid'];
    $sql="SELECT schedule.id AS schedule_id, patient.id AS patient_id, schedule.*, patient.*
    FROM schedule INNER JOIN patient ON `schedule`.patient_id = patient.id WHERE patient.id = $patientid;";
    $result=mysqli_query($conn,$sql);
    $profile="SELECT * FROM patient WHERE id=$patientid";
    $profileresult=mysqli_query($conn,$profile);
    $rows=mysqli_fetch_assoc(($profileresult));
    $MI = mb_substr($rows['mname'], 0, 1);

    $dates="SELECT schedule.id AS schedule_id, patient.id AS patient_id, schedule.*, patient.*, 
    (SELECT MIN(date_start) FROM schedule WHERE patient_id = $patientid) AS earliest_date, 
    (SELECT MAX(date_start) FROM schedule WHERE patient_id = $patientid) AS latest_date 
    FROM schedule INNER JOIN patient ON `schedule`.patient_id = patient.id WHERE patient.id = $patientid;";
    $datecompare=mysqli_query($conn,$dates);
    
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
<a class="nav-item" href="patientList.php"><img src="arrow_back_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="" width="50px" style="position:fixed;"></a>
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

<!-- container for profile display -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-white text-center" style="width: 25%;margin-left:140px; height:100vh; position:fixed;background-color:rgb(78, 116, 242);">
<img src="account_circle_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="" width="350px" style="margin-left: 40px;">
<?php echo '<h2>'.$rows['fname'].' '.$MI.'. '.$rows['lname'].'</h2>'; ?> <br>
<div style="text-align: left;">
<h2><?php $dateOfBirth = date('Y-m-d',strtotime($rows['birthdate']));
$today = date("Y-m-d");
$diff = date_diff(date_create($dateOfBirth), date_create($today));
echo 'Age : '.$diff->format('%y'); ?></h2></div>
<div style="text-align: left;"><h2>
<?php echo 'Phone number: '. $rows['phone_number'] ?>
</h2></div>
<?php
if (mysqli_num_rows($datecompare) > 0) {
    $rowss=mysqli_fetch_assoc(($datecompare));
    ?>
<div style="text-align: left;"><h2><?php echo 'First Appointment: '.date('m/d/Y',strtotime($rowss['earliest_date'])) ?></h2></div>
<div style="text-align: left;"><h2><?php echo 'Last Appointment: '.date('m/d/Y',strtotime($rowss['latest_date'])) ?></h2></div>
    <?php
} else {
    ?>
<h2>No records Exists</h2>
    <?php
}
?>
</div>

<!-- ghost sidebar -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 140px; height:100vh;">ghost</div> 
<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 25%; height:100vh;">ghost</div> 


<div class="container-fluid" style="background-color: lightgray;"> 
<h1 style="padding: 50px;">Patient History</h1>

<div class="container-fluid shadow-lg rounded" style="background-color:white;padding:50px">
<table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Appointment</th>
      <th scope="col">Status</th>
      <th scope="col">Dentist</th>
    </tr>
  </thead>
  <tbody>

<?php
    
    if (mysqli_num_rows($result) > 0) {
        while ($row=mysqli_fetch_assoc(($result))) {
            $id=$row['id'];
            echo '<tr>
            <th scope="row">'.date('m/d/Y',strtotime($row['date_start'])).'</th>
            <td>'.$row['visit_type'].'</td>
            <td>'.$row['status'].'</td>
            <td>'.$row['dentist'].'</td> 
                </tr>';
        }
    } else {
        echo '<tr>
            <td colspan="4">No Records</td>
                </tr>';
    }
    // SELECT schedule.id AS schedule_id, patient.id AS patient_id, schedule.*, patient.* FROM schedule INNER JOIN patient ON `schedule`.patient_id = patient.id;
?>
    
  </tbody>
</table>
</div>
</div>
</div><!-- end of entire container -->
</body>
</html>