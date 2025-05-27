<?php
include('connect.php');
include('verified.php');

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';

if (isset($_GET['dish'])) {
    $dish_id = htmlspecialchars($_GET['dish']);
    
    // Handle dish delete
    if (isset($_GET['delete-record'])) {
        $deleteDate = $_GET['delete-date'];
        $deleteQuery = "DELETE FROM inventory_daily_record WHERE inventory_pack_id = '$dish_id' AND date = '$deleteDate'";
        mysqli_query($conn, $deleteQuery);
    }

    // Fetch dish details
    $dishQuery = "SELECT name, price FROM inventory_pack WHERE inventory_pack_id = '$dish_id'";
    $dishResult = mysqli_query($conn, $dishQuery);
    if ($dishRow = mysqli_fetch_assoc($dishResult)) {
        $dish_name = $dishRow['name'];
        $dish_price = $dishRow['price'];
    } else {
        $dish_name = "Unknown Dish";
        $dish_price = 0;
    }

    // Pagination logic
    $limit = 9; // Number of entries to show per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = max(0, ($page - 1) * $limit);

    $totalQuery = "SELECT COUNT(*) AS total FROM inventory_daily_record WHERE inventory_pack_id = '$dish_id'";
    $totalResult = mysqli_query($conn, $totalQuery);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $total = $totalRow['total'];
    $pages = ceil($total / $limit);

    // Fetch daily records with sorting and pagination
    $recordQuery = "SELECT * FROM inventory_daily_record WHERE inventory_pack_id = '$dish_id' ORDER BY $sort_by $sort_order LIMIT $start, $limit";
    $recordResults = mysqli_query($conn, $recordQuery);
} else {
    echo "<script>window.location.href='inventory.php';</script>";
}

// Handle adding a new record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-record'])) {
    $date = htmlspecialchars($_POST['rec-date']);
    $additionalQuantity = htmlspecialchars($_POST['additional-quantity']);
    $soldQuantity = htmlspecialchars($_POST['sold-quantity']);
    $wastedQuantity = htmlspecialchars($_POST['wasted-quantity']);

    $compareRecordQuery = "SELECT `date` FROM inventory_daily_record WHERE `date` = '$date' AND inventory_pack_id = '$dish_id'";
    $compareRecordResult = mysqli_query($conn, $compareRecordQuery);
    if (mysqli_num_rows($compareRecordResult) == 0) {
        // Fetch previous day's ending quantity to set as starting quantity
        $prevDayQuery = "SELECT ending_quantity FROM inventory_daily_record WHERE inventory_pack_id = '$dish_id' AND date < '$date' ORDER BY date DESC LIMIT 1";
        $prevDayResult = mysqli_query($conn, $prevDayQuery);
        if ($prevDayRow = mysqli_fetch_assoc($prevDayResult)) {
            $startQuantity = $prevDayRow['ending_quantity'];
        } else {
            $startQuantity = 0; // Default value if no previous record exists
        }

        $endingQuantity = $startQuantity + $additionalQuantity - $soldQuantity - $wastedQuantity;
        $totalSales = $soldQuantity * $dish_price;

        $insertRecordQuery = "INSERT INTO inventory_daily_record
                                (inventory_pack_id, date, starting_quantity, additional_quantity, sold_quantity, wasted_quantity, ending_quantity, total_sales) 
                                VALUES ('$dish_id', '$date', '$startQuantity', '$additionalQuantity', '$soldQuantity', '$wastedQuantity', '$endingQuantity', '$totalSales')";
        if (mysqli_query($conn, $insertRecordQuery)) {
            echo "<script>alert('Record added successfully'); window.location.href='dishRecord.php?dish=$dish_id';</script>";
        } else {
            echo "<script>alert('Error adding record: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Record for the given date already exists! Please edit the record to apply change.');</script>";
    }
}

// Handle editing a record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit-record'])) {
    $recordId = htmlspecialchars($_POST['record-id']);
    $date = htmlspecialchars($_POST['edit-rec-date']);
    $additionalQuantity = htmlspecialchars($_POST['edit-additional-quantity']);
    $soldQuantity = htmlspecialchars($_POST['edit-sold-quantity']);
    $wastedQuantity = htmlspecialchars($_POST['edit-wasted-quantity']);

    // Fetch previous day's ending quantity to set as starting quantity
    $prevDayQuery = "SELECT ending_quantity FROM inventory_daily_record WHERE inventory_pack_id = '$dish_id' AND date < '$date' ORDER BY date DESC LIMIT 1";
    $prevDayResult = mysqli_query($conn, $prevDayQuery);
    if ($prevDayRow = mysqli_fetch_assoc($prevDayResult)) {
        $startQuantity = $prevDayRow['ending_quantity'];
    } else {
        $startQuantity = 0; // Default value if no previous record exists
    }

    $endingQuantity = $startQuantity + $additionalQuantity - $soldQuantity - $wastedQuantity;
    $totalSales = $soldQuantity * $dish_price;

    $updateRecordQuery = "UPDATE inventory_daily_record SET date='$date', starting_quantity='$startQuantity', additional_quantity='$additionalQuantity', sold_quantity='$soldQuantity', wasted_quantity=$wastedQuantity, ending_quantity='$endingQuantity', total_sales='$totalSales' WHERE date='$date' AND inventory_pack_id='$recordId'";
    if (mysqli_query($conn, $updateRecordQuery)) {
        echo "<script>alert('Record updated successfully'); window.location.href='dishRecord.php?dish=$dish_id';</script>";
    } else {
        echo "<script>alert('Error updating record: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $dish_name; ?> - Daily Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href='style.css?v<?php echo time(); ?>'>
</head>
<body>
    <div class="main-content">
    <aside class="sidebar">
        <div>
            <div class="brand">
                <img src="logo.png" alt="Lobster Grill Logo">
                <div class="brand-name">
                    <h1>Lobster Grill</h1>
                </div>
            </div>
            <div class="profile1">
                <img src="<?php echo $icon_path; ?>" alt="Profile Picture">
                <div class="profile-info">
                    <h2><?php echo $user_fname . ' ' . $user_lname; ?></h2>
                    <p><?php echo $role_name; ?></p>
                </div>
            </div>
            <nav class="nav-links">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                <a href="inventory.php" class="active"><i class="fas fa-boxes"></i>Inventory</a>
                <?php if ($role_name === 'Owner' || $role_name === 'Manager'): ?>
                    <a href="supplier.php"><i class="fas fa-truck"></i>Suppliers</a>
                <?php endif; ?>
                <?php if ($role_name === 'Owner'): ?>
                    <a href="reports.php"><i class="fas fa-chart-line"></i>Reports</a>
                <?php endif; ?>
                <?php if ($role_name === 'Owner'): ?>
                    <a href="employees.php"><i class="fas fa-users"></i>Employees</a>
                <?php endif; ?>
                <a href="logout.php" class="sign-out"><i class="fas fa-sign-out-alt"></i>Sign out</a>
            </nav>
            </div>
        </aside>
        
        <main class="main">
            <header class="header">
                <h1><?php echo $dish_name; ?></h1>
                <div class="controls">
                    <div class="sort-container">
                        <i class="fas fa-sort sort-icon"></i>
                        <select id="sort-options" class="sort-btn">
                            <option value="" disabled selected>Sort by</option>
                            <option value="date_DESC">Date (Newest to Oldest)</option>
                            <option value="date_ASC">Date (Oldest to Newest)</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" id="add-new-rec-btn">Record</button>
                </div>
            </header>

            <div class="container">
                <div class="content">
                    <section class="record">
                        <table class="record-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Starting Quantity</th>
                                    <th>Additional Quantity</th>
                                    <th>Sold Quantity</th>
                                    <th>Wasted Quantity</th>
                                    <th>Ending Quantity</th>
                                    <th>Sales</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($record = mysqli_fetch_assoc($recordResults)): ?>
                                    <tr>
                                        <td><?php echo $record['date']; ?></td>
                                        <td><?php echo $record['starting_quantity']; ?></td>
                                        <td><?php echo $record['additional_quantity']; ?></td>
                                        <td><?php echo $record['sold_quantity']; ?></td>
                                        <td><?php echo $record['wasted_quantity']; ?></td>
                                        <td><?php echo $record['ending_quantity']; ?></td>
                                        <td><?php echo $record['total_sales']; ?></td>
                                        <td>
                                            <button class="rec-edit-btn" data-id="<?php echo $dish_id; ?>" data-date="<?php echo $record['date']; ?>" data-start-quantity="<?php echo $record['starting_quantity']; ?>" data-additional-quantity="<?php echo $record['additional_quantity']; ?>" data-sold-quantity="<?php echo $record['sold_quantity']; ?>" data-wasted-quantity="<?php echo $record['wasted_quantity']; ?>" data-ending-quantity="<?php echo $record['ending_quantity']; ?>" data-sales="<?php echo $record['total_sales']; ?>"><i class="fas fa-edit"></i></button>
                                            <?php if ($role_name === 'Owner' || $role_name === 'Manager'): ?><button class="rec-delete-btn" data-id="<?php echo $dish_id; ?>" data-date="<?php echo $record['date']; ?>"><i class="fas fa-trash"></i></button><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <!-- Pagination Bar -->
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="dishRecord.php?dish=<?php echo $dish_id; ?>&page=<?php echo $page - 1; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>" class="pagination-link">Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pages; $i++): ?>
                                <a href="dishRecord.php?dish=<?php echo $dish_id; ?>&page=<?php echo $i; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>" class="pagination-link <?php if ($page == $i) echo 'active'; ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $pages): ?>
                                <a href="dishRecord.php?dish=<?php echo $dish_id; ?>&page=<?php echo $page + 1; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>" class="pagination-link">Next</a>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Record Modal -->
    <div id="record-modal" class="modal">
        <div class="modal-content">
            <h2>Add Record</h2>
            <form id="record-form" method="POST" action="">
                <div class="form-group">
                    <label for="rec-date">Date</label>
                    <input type="date" value="<?php echo date("Y-m-d") ?>" id="rec-date" name="rec-date" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="additional-quantity">Additional Quantity</label>
                        <input type="number" min="0" id="additional-quantity" name="additional-quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="sold-quantity">Sold Quantity</label>
                        <input type="number" min="0" id="sold-quantity" name="sold-quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="wasted-quantity">Wasted Quantity</label>
                        <input type="number" min="0" id="wasted-quantity" name="wasted-quantity" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="rec-cancel-btn">Cancel</button>
                    <button type="submit" name="add-record" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Record Modal -->
    <div id="edit-record-modal" class="modal">
        <div class="modal-content">
            <h2>Edit Record</h2>
            <form id="edit-record-form" method="POST" action="">
                <input type="hidden" id="record-id" name="record-id">
                <div class="form-group">
                    <label for="edit-rec-date">Date</label>
                    <input type="date" id="edit-rec-date" name="edit-rec-date" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-additional-quantity">Additional Quantity</label>
                        <input type="number" min="0" id="edit-additional-quantity" name="edit-additional-quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-sold-quantity">Sold Quantity</label>
                        <input type="number" min="0" id="edit-sold-quantity" name="edit-sold-quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-wasted-quantity">Wasted Quantity</label>
                        <input type="number" min="0" id="edit-wasted-quantity" name="edit-wasted-quantity" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="edit-rec-cancel-btn">Cancel</button>
                    <button type="submit" name="edit-record" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

<script src="script.js"></script>
</body>
</html>
