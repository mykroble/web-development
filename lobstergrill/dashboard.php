<?php
include('connect.php');
include('verified.php');

// Fetch total sales from the database
$totalSalesQuery = "SELECT SUM(total_sales) as total_sales FROM inventory_daily_record WHERE date = CURDATE()";
$totalSalesResult = mysqli_query($conn, $totalSalesQuery);
$totalSalesRow = mysqli_fetch_assoc($totalSalesResult);
$totalSales = $totalSalesRow['total_sales'] ? $totalSalesRow['total_sales'] : 0;

$shortStockQuery = "SELECT latest_inventory.name AS names
FROM(
    SELECT idr.inventory_pack_id, idr.ending_quantity, ip.minquantity, ip.name
    FROM inventory_daily_record idr
    JOIN(
        SELECT inventory_pack_id, MAX(date) AS latest_date
        FROM inventory_daily_record
        GROUP BY inventory_pack_id
    ) AS latest_records
    ON idr.inventory_pack_id = latest_records.inventory_pack_id AND idr.date = latest_records.latest_date
    JOIN inventory_pack ip
    ON idr.inventory_pack_id = ip.inventory_pack_id
) AS latest_inventory
WHERE latest_inventory.ending_quantity < latest_inventory.minquantity;";
$shortStockResult = mysqli_query($conn, $shortStockQuery);
$shortStockNames = [];
while ($row = mysqli_fetch_assoc($shortStockResult)) {
    $shortStockNames[] = $row['names'];
}
$shortStockCount = mysqli_num_rows($shortStockResult);
$recordTodayQuery = "SELECT ip.name, idr.starting_quantity, idr.additional_quantity, idr.sold_quantity, idr.wasted_quantity, idr.ending_quantity, idr.total_sales FROM inventory_daily_record idr JOIN inventory_pack ip ON idr.inventory_pack_id = ip.inventory_pack_id WHERE date = CURDATE() ORDER BY idr.inventory_pack_id ASC";
$recordTodayResults = mysqli_query($conn, $recordTodayQuery);
// $recordTodayRow = mysqli_fetch_assoc($recordTodayResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobster Grill Dashboard</title>
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
                <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                <a href="inventory.php"><i class="fas fa-boxes"></i>Inventory</a>
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
            <h2>Welcome back, <?php echo $user_fname; ?></h2>
            </header>
            <p>Here are today's daily overview</p>
            <section class="overview">
                <div class="card total_sales">
                    <div class="card-details">
                        <h3>Total Item Sales</h3>
                        <p>₱ <?php echo number_format($totalSales, 2); ?></p>
                    </div>
                </div>
                <div class="card low_stock" id="card-low-stock">
                    <div class="card-details">
                        <h3>Low Stock Items</h3>
                        <p><?php echo $shortStockCount; ?></p>
                    </div>
                </div>
                <!-- <div class="card expired_stock">
                    <div class="card-details">
                        <h3>Expired Stock Items</h3>
                        <p>2</p>
                    </div>
                </div> -->
            </section>
            <div class="container">
                <div class="content">
                    <h2>Today's daily record:</h2>
                        <section class="record">
                            <table class="record-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Starting Quantity</th>
                                        <th>Additional Quantity</th>
                                        <th>Sold Quantity</th>
                                        <th>Wasted Quantity</th>
                                        <th>Ending Quantity</th>
                                        <th>Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recordTodayResults as $result): ?>
                                        <tr>
                                            <td><?php echo $result['name']; ?></td>
                                            <td><?php echo $result['starting_quantity']; ?></td>
                                            <td><?php echo $result['additional_quantity']; ?></td>
                                            <td><?php echo $result['sold_quantity']; ?></td>
                                            <td><?php echo $result['wasted_quantity']; ?></td>
                                            <td><?php echo $result['ending_quantity']; ?></td>
                                            <td><?php echo $result['total_sales']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>

        </main>
    </div>

<div class="modal" id="low-stock-modal">
    <div class="modal-content">
        <h2>List of Low Stock Items:</h2>
        <?php foreach($shortStockNames as $name): ?>
            <div class="form-row">
                <p><?php echo $name; ?></p>
            </div>
            <ul>
        <?php endforeach; ?>
        <div class="form-actions">
            <button type="button" class="btn" id="low-stock-close-btn">Got it!</button>
        </div>
    </div>
</div>
<script src="script.js"></script>
</body>
</html>
