<?php
    include('connect.php');
    $id=$_GET['userid'];
    $sql="SELECT * FROM `patient` WHERE id='$id'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    $MI = mb_substr($row['mname'], 0, 1);

    if(isset($_POST['submit'])) {
      $day = filter_input(INPUT_POST, 'day', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^(0?[1-9]|[1-2][0-9]|3[0-1])$/"]]);
      $month = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^(0?[1-9]|1[0-2])$/"]]);
      $year = $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1900, "max_range" => date("Y")]]);
      $date = null;
      if ($day && $month && $year) {
    // Ensure leading zeros for day and month
      $day_padded = str_pad($day, 2, "0", STR_PAD_LEFT);
      $month_padded = str_pad($month, 2, "0", STR_PAD_LEFT);
      $date = sprintf('%04d-%02d-%02d', $year, $month_padded, $day_padded);
      if (!checkdate($month, $day, $year)) {
          $date = null; // Invalid date
      }
    } 
      $start=$_POST['start'];
      $end=$_POST['end'];
      $visit_type=filter_input(INPUT_POST, 'visit_type', FILTER_SANITIZE_SPECIAL_CHARS);
      $start_time = $date.' '.$start.':00';
      $end_time = $date.' '.$end.':00';
      $dentist=filter_input(INPUT_POST, 'dentist', FILTER_SANITIZE_SPECIAL_CHARS);
      $status="PENDING";
      echo date('h:i a m/d/Y',strtotime($start_time));
      echo date('h:i a m/d/Y',strtotime($end_time));

      if (empty($start_time) || empty($end_time) || empty($visit_type) || empty($dentist)) {

      } else {
        $sql="INSERT INTO `schedule`(`patient_id`, `visit_type`, `date_start`, `date_end`, `dentist`, `status`) 
      VALUES ('$id','$visit_type','$start_time','$end_time','$dentist','$status')";
      $result=mysqli_query($conn,$sql);
      if ($result) {
        header('location:schedule.php');
      } else {
        echo "bruh";
      }
      }

      
    } else {

    }
?>
<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>create sched</title>
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
<a class="nav-item" href="searchPatient.php"><img src="arrow_back_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="" width="50px" style="position:fixed;"></a>
<ul class="nav flex-column" style="margin-top: 140px;">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="schedule.php"><img src="calendar_month_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="#" width="70px"></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="patientList.php"><img src="id_card_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="" width="70px"></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="createPatient.php"><img src="person_add_24dp_FILL0_wght400_GRAD0_opsz24.png" alt="" width="70px"></a>
  </li>
</ul></div>
<!-- ghost sidebar -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 140px; height:100vh;">ghost</div> 



<!-- container for content -->
<div class="container-fluid" style="background-color: lightgray;"> 
<h1 style="padding: 50px;">Create Schedule</h1>
<div class="container-fluid shadow-lg rounded" style="background-color:white;padding:50px;">


<form method="post" class="needs-validation" novalidate>
  <h2><?php echo $row['fname'].' '.$MI.'. '.$row['lname'] ?></h2>
  <div class="form-row">
  <div class="col-md-6 mb-3">
    <label for="validationCustom01">Visit Type</label>
    <input type="text" class="form-control" required id="validationCustom01" id="formGroupExampleInput" placeholder="Enter Visit Type Here" name="visit_type">
  </div>
  <div class="col-md-2 mb-3">
      <label for="validationCustom02">Date of Schedule</label>
    <input class="form-control" required id="validationCustom02" type="number" min="1" max="31" placeholder="DD" name="day"> 
    </div>
    <div class="col-md-2 mb-3">
    <label for="validationCustom03" style="color:white;">.</label>
    <input class="form-control" required id="validationCustom03" type="number" min="1" max="12" placeholder="MM" name="month">
    </div>
    <div class="col-md-2 mb-3">
    <label for="validationCustom4" style="color:white;">.</label>
    <input class="form-control" required id="validationCustom04" type="number" min="1900" max="<?php echo date('Y'); ?>" placeholder="YYYY" name="year">
    </div>
</div>
<div class="form-row">
<div class="col-md-3 mb-3">
    <label for="validationCustom5" for="appt">Select a time:</label>
    <input required id="validationCustom05" type="time" class="form-control" id="appt" name="start">
    </div>
    <div class="col-md-0 mb-0">
    <label for="validationCustom06" style="color:white;">.</label>
    <h3>-</h3>
    </div>
    <div class="col-md-3 mb-3">
    <label for="validationCustom06" style="color:white;">.</label>
    <input required id="validationCustom06" class="form-control" type="time" id="appt" name="end">
    </div>
    <div class="col-md-3 mb-3">
  <label for="validationCustom07">Dentist-In-Charge</label>
  <select required id="validationCustom07" class="form-control" name="dentist">
  <option selected disabled value="">Choose...</option>
  <option value="Dr. Sainz">Dr. Sainz</option>
  <option value="Dr. Norris">Dr. Norris</option>
  <option value="Dr. Alonso">Dr. Alonso</option>
    </select>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2" name="submit">Add to Schedule</button>
</form>
</div> <!-- end of form container -->
</div><!-- end of content container -->
</div><!-- end of entire container -->
</body>
</html>