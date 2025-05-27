<?php
    include('connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>

    <!-- Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Entire Container -->
<div style="display: flex; background-color: lightgray;">

    <!-- Sidebar fixed -->
    <div class="d-flex flex-column flex-shrink-0 p-3 text-dark bg-light" style="width: 140px; height:100vh; position:fixed;">
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
        </ul>
    </div>

    <!-- Ghost sidebar for spacing -->
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 140px; height:100vh;">ghost</div>

    <!-- Main content -->
    <div class="container-fluid" style="background-color: lightgray;">
        <h1 style="padding: 15px;">Schedule</h1>
        <div class="container-fluid" style="padding: 15px;">
            <button type="button" class="btn btn-primary"><a href="searchPatient.php" style="text-decoration: none; color: white;">Add patient</a></button>
        </div>

        <!-- Table container -->
        <div class="container-fluid shadow-lg rounded" style="background-color:white;padding:50px">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Visit Type</th>
                        <th scope="col">Contact Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>

<?php
    $sql = "SELECT schedule.id AS schedule_id, patient.id AS patient_id, schedule.*, patient.*
            FROM schedule
            INNER JOIN patient ON schedule.patient_id = patient.id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['schedule_id'];
            $MI = mb_substr($row['mname'], 0, 1);
            if (strcmp($row['status'], "COMPLETED") != 0) {
                echo '<tr>
                <th scope="row">'.$id.'</th>
                <td>'.$row['fname'].' '.$MI.'. '.$row['lname'].'</td>
                <td>'.$row['visit_type'].'</td>
                <td>'.$row['phone_number'].'</td>
                <td>'.date('l m/d/Y', strtotime($row['date_start'])).'</td>
                <td>'.date('h:i a', strtotime($row['date_start'])).'-'.date('h:i a', strtotime($row['date_end'])).'</td>
                <td>
                    <div class="dropdown show">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.$row['status'].'
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="changeStatus.php?scheduleid='.$id.'&statusChange=PENDING">Pending</a>
                            <a class="dropdown-item" href="changeStatus.php?scheduleid='.$id.'&statusChange=ARRIVED">Arrived</a>
                            <a class="dropdown-item" href="changeStatus.php?scheduleid='.$id.'&statusChange=LATE">Late</a>
                            <a class="dropdown-item" href="changeStatus.php?scheduleid='.$id.'&statusChange=NO SHOW">No Show</a>
                            <a class="dropdown-item" href="changeStatus.php?scheduleid='.$id.'&statusChange=COMPLETED">Completed</a>
                        </div>
                    </div>
                </td>
                </tr>';
            }
        }
    }
?>

</tbody>
</table>
</div> <!-- end of table container -->
</div> <!-- end of content container -->
</div> <!-- end of entire container -->

</body>
</html>
