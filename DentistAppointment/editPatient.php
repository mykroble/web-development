<?php
    include("connect.php");
    $id=$_GET['updateid'];
    $sql="SELECT * FROM `patient` WHERE id='$id'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    if (isset($_POST['submit'])) {
      $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS);
      $mname = filter_input(INPUT_POST, 'mname', FILTER_SANITIZE_SPECIAL_CHARS);
      $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS);
      $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS);
      $day = filter_input(INPUT_POST, 'day', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^(0?[1-9]|[1-2][0-9]|3[0-1])$/"]]);
      $month = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^(0?[1-9]|1[0-2])$/"]]);
      $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1900, "max_range" => date("Y")]]);
      $phone_number = filter_input(INPUT_POST, 'contactNumber', FILTER_SANITIZE_SPECIAL_CHARS); // You may want to use regex for stricter validation
      $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_SPECIAL_CHARS);
      $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_SPECIAL_CHARS);
      $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
      $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_SPECIAL_CHARS);
      $marital_status = filter_input(INPUT_POST, 'maritalStatus', FILTER_SANITIZE_SPECIAL_CHARS);
      $occupation = filter_input(INPUT_POST, 'occupation', FILTER_SANITIZE_SPECIAL_CHARS);
      
      $birthdate = null;
      if ($day && $month && $year) {
    // Ensure leading zeros for day and month
      $day_padded = str_pad($day, 2, "0", STR_PAD_LEFT);
      $month_padded = str_pad($month, 2, "0", STR_PAD_LEFT);
      $birthdate = sprintf('%04d-%02d-%02d', $year, $month_padded, $day_padded);
      if (!checkdate($month, $day, $year)) {
          $birthdate = null; // Invalid date
      }
    } 
      
      // Example of custom phone number validation (optional, depending on your needs)
      if (!preg_match('/^[0-9\-\(\)\/\+\s]*$/', $phone_number)) {
          $phone_number = null; // Invalid phone number
      }
      
      // Ensure all required fields are not empty
      if (empty($fname) || empty($mname) || empty($lname) || empty($gender) || !$birthdate || empty($phone_number) || empty($street) || empty($barangay) || empty($city) || empty($province) || empty($marital_status) || empty($occupation)) {
          // Handle validation errors
      } else {
        $sql="UPDATE `patient` SET fname='$fname',mname='$mname',lname='$lname',gender='$gender',birthdate='$birthdate',
        phone_number='$phone_number',street='$street',barangay='$barangay',city='$city',province='$province', marital_status='$marital_status',
        occupation='$occupation' WHERE id='$id'";
        $result=mysqli_query($conn,$sql);
        if ($result) {
            header('location:patientList.php');
            echo "its in the database now";
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
    <title>Create patient</title>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div style="display: flex; background-color:lightgray;">
<!-- sidebar container -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-dark bg-light" style="width: 140px; height:100vh; position:fixed;"> <!-- container for sidebar fixed-->
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
<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 140px; height:100vh;">ghost</div> <!-- ghost sidebar -->

<div class="container-fluid" style="background-color: lightgray;">  <!-- container for content -->
<h1 style="padding: 50px;">Edit Patient</h1>
<div class="container-fluid shadow-lg rounded" style="background-color:white;padding:50px;">
<form method="post" class="needs-validation" novalidate>
  <!-- row 1 -->
  <div class="form-row">
  <div class="col-md-4 mb-3">
    <label for="validationCustom01">First Name</label>
    <input type="text" class="form-control" required id="validationCustom01" placeholder="Enter First Name Here" name="fname" value="<?php echo $row['fname'] ?>">
    </div>
    <div class="col-md-4 mb-3">
    <label for="validationCustom02">Middle Name</label>
    <input type="text" class="form-control" required id="validationCustom02" placeholder="Enter Middle Name Here" name="mname" value="<?php echo $row['mname'] ?>">
    </div>
    <div class="col-md-4 mb-3">
    <label for="validationCustom03">Last Name</label>
    <input type="text" class="form-control" required id="validationCustom03" placeholder="Enter Last Name Here" name="lname" value="<?php echo $row['lname'] ?>">
  </div>
  </div>
  
  <!-- row 2 -->
  <div class="form-row">
  <div class="col-md-4 mb-3">
  <label for="validationCustom04">Gender</label>
  <select class="form-control" required id="validationCustom04" name="gender">
  <option disabled value="">Choose...</option>
  <option <?php if($row['gender'] == "Male") { echo 'selected';} ?> value="Male">Male</option>
  <option <?php if($row['gender'] == "Female") { echo 'selected';} ?> value="Female">Female</option>
    </select>
</div>
<div class="col-md-4 mb-3">
<div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationCustom05">Birth Date</label>
    <input class="form-control" required id="validationCustom05" type="number" min="1" max="31" placeholder="DD" name="day" value="<?php echo date('d',strtotime($row['birthdate'])); ?>"> 
    </div>
    <div class="col-md-4 mb-3">
    <label for="validationCustom06" style="color:white;">.</label>
    <input class="form-control" required id="validationCustom06" type="number" min="1" max="12" placeholder="MM" name="month" value="<?php echo date('m',strtotime($row['birthdate'])); ?>">
    </div>
    <div class="col-md-4 mb-3">
    <label for="validationCustom07" style="color:white;">.</label>
    <input class="form-control" required id="validationCustom07" type="number" min="1" max="9999" placeholder="YYYY" name="year" value="<?php echo date('Y',strtotime($row['birthdate'])); ?>">
    </div>
</div>
</div>
<div class="col-md-4 mb-3">
    <label for="validationCustom08">Phone Number</label>
    <input type="text" class="form-control" required id="validationCustom08" id="formGroupExampleInput" placeholder="Enter Phone Number Here" maxlength="11" name="contactNumber" value="<?php echo $row['phone_number'] ?>">
</div>
</div>

<!-- row 3 -->
<div class="form-row">
<div class="col-md-3 mb-3">
    <label for="validationCustom09">Street</label>
    <input type="text" class="form-control" required id="validationCustom09" id="formGroupExampleInput" placeholder="Enter Street Name Here" name="street" value="<?php echo $row['street'] ?>">
  </div>
  <div class="col-md-3 mb-3">
    <label for="validationCustom10">Barangay</label>
    <input type="text" class="form-control" required id="validationCustom10" id="formGroupExampleInput" placeholder="Enter Barangay Name Here" name="barangay" value="<?php echo $row['barangay'] ?>">
  </div>
  <div class="col-md-3 mb-3">
    <label for="validationCustom11">City</label>
    <input type="text" class="form-control" required id="validationCustom11" id="formGroupExampleInput" placeholder="Enter City Name Here" name="city" value="<?php echo $row['city'] ?>">
  </div>
  <div class="col-md-3 mb-3">
    <label for="validationCustom12">Province</label>
    <input type="text" class="form-control" required id="validationCustom12" id="formGroupExampleInput" placeholder="Enter Province Name Here" name="province" value="<?php echo $row['province'] ?>">
  </div>
  </div>

  <div class="form-group">
  <label for="validationCustom13">Marital Status</label>
  <select required id="validationCustom13" class="form-control" name="maritalStatus">
  <option selected disabled value="">Choose...</option>
  <option <?php if($row['marital_status'] == "Married") { echo 'selected';} ?> value="Married">Married</option>
  <option <?php if($row['marital_status'] == "Not Married") { echo 'selected';} ?> value="Not Married">Not Married</option>
    </select>
  </div>
  <div class="form-group">
    <label for="validationCustom13">Occupation</label>
    <input type="text" required id="validationCustom13" class="form-control" id="formGroupExampleInput" placeholder="Enter Occupation Here" name="occupation" value="<?php echo $row['occupation'] ?>">
  </div>
  <button type="submit" class="btn btn-primary mb-2" name="submit">Create client record</button>
</form>


<!-- end of content div -->
</div>

</div>
</body>
</html>