<?php
include('connect.php');
include('verified.php');

// Handle form submission for adding a new record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-record'])) {
    $materialName = htmlspecialchars($_POST['material-name']);
    $weight = htmlspecialchars($_POST['weight-in-kg']);
    $deliveryDate = htmlspecialchars($_POST['rec-date']);

    // Get supplier_id for the material
    $stmt = $conn->prepare("SELECT supplier_id FROM raw_materials WHERE material_name = ?");
    $stmt->bind_param("s", $materialName);
    $stmt->execute();
    $stmt->bind_result($supplierId);
    $stmt->fetch();
    $stmt->close();

    if (!$supplierId) {
        echo json_encode(['status' => 'error', 'message' => 'Supplier not found for the material']);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO raw_materials (supplier_id, material_name, weight_in_kg, delivery_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $supplierId, $materialName, $weight, $deliveryDate);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
}

// Handle form submission for editing a record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit-record'])) {
    $recordId = htmlspecialchars($_POST['record-id']);
    $materialName = htmlspecialchars($_POST['material-name']);
    $weight = htmlspecialchars($_POST['weight-in-kg']);
    $deliveryDate = htmlspecialchars($_POST['edit-date']);

    // Get supplier_id for the material
    $stmt = $conn->prepare("SELECT supplier_id FROM raw_materials WHERE material_name = ?");
    $stmt->bind_param("s", $materialName);
    $stmt->execute();
    $stmt->bind_result($supplierId);
    $stmt->fetch();
    $stmt->close();

    if (!$supplierId) {
        echo json_encode(['status' => 'error', 'message' => 'Supplier not found for the material']);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("UPDATE raw_materials SET supplier_id = ?, material_name = ?, weight_in_kg = ?, delivery_date = ? WHERE raw_material_id = ?");
    $stmt->bind_param("isisi", $supplierId, $materialName, $weight, $deliveryDate, $recordId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
}

// Handle form submission for deleting a record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete-record'])) {
    $recordId = htmlspecialchars($_POST['record-id']);

    $stmt = $conn->prepare("DELETE FROM raw_materials WHERE raw_material_id = ?");
    $stmt->bind_param("i", $recordId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
}

// Fetch raw materials to display in the table
$result = mysqli_query($conn, "
    SELECT 
        raw_materials.raw_material_id, 
        suppliers.supplier_name, 
        raw_materials.material_name, 
        raw_materials.weight_in_kg, 
        raw_materials.delivery_date 
    FROM raw_materials 
    JOIN suppliers ON raw_materials.supplier_id = suppliers.supplier_id
");

$raw_materials = mysqli_fetch_all($result, MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Logs - Raw Materials</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href='style.css?v=<?php echo time(); ?>'>
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
                <h2>Delivery Logs</h2>
                <div class="controls">
                    <div class="sort-container">
                        <i class="fas fa-sort sort-icon"></i>
                        <select id="sort-options" class="sort-btn">
                            <option value="" disabled selected>Sort by</option>
                            <option value="date_DESC">Date (Newest to Oldest)</option>
                            <option value="date_ASC">Date (Oldest to Newest)</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" id="add-new-deli-btn">Record</button>
                </div>
            </header>

            <div class="container">
                <div class="content">
                    <section class="delivery-logs">
                        <table class="deli-table">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Material Name</th>
                                    <th>Weight (kg)</th>
                                    <th>Delivery Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($raw_materials as $material): ?>
                                    <tr>
                                        <td><?php echo $material['supplier_name']; ?></td>
                                        <td><?php echo $material['material_name']; ?></td>
                                        <td><?php echo $material['weight_in_kg']; ?></td>
                                        <td><?php echo $material['delivery_date']; ?></td>
                                        <td>
                                            <button class="deli-edit-btn" data-id="<?php echo $material['raw_material_id']; ?>" data-name="<?php echo $material['material_name']; ?>" data-weight="<?php echo $material['weight_in_kg']; ?>" data-date="<?php echo $material['delivery_date']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="deli-delete-btn" data-id="<?php echo $material['raw_material_id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Record Modal -->
    <div id="record-deli-modal" class="modal">
        <div class="modal-content">
            <h2>Add Record</h2>
            <form id="deli-record-form" method="POST">
                <input type="hidden" name="add-record" value="1">
                <div class="form-row">
                    <div class="form-group">
                        <label for="material-name">Material Name</label>
                        <input type="text" id="material-name" name="material-name" required>
                    </div>
                    <div class="form-group">
                        <label for="weight-in-kg">Weight (kg)</label>
                        <input type="number" id="weight-in-kg" name="weight-in-kg" required>
                    </div>
                    <div class="form-group">
                        <label for="rec-deli-date">Date</label>
                        <input type="date" value="<?php echo date('Y-m-d'); ?>" id="rec-date" name="rec-date" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="deli-cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Record Modal -->
    <div id="edit-deli-record-modal" class="modal">
        <div class="modal-content">
            <h2>Edit Record</h2>
            <form id="edit-deli-record-form" method="POST">
                <input type="hidden" id="record-id" name="record-id">
                <input type="hidden" name="edit-record" value="1">
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-material-name">Material Name</label>
                        <input type="text" id="edit-material-name" name="material-name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-weight-in-kg">Weight (kg)</label>
                        <input type="number" id="edit-weight-in-kg" name="weight-in-kg" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-deli-date">Date</label>
                        <input type="date" value="<?php echo date('Y-m-d'); ?>" id="edit-date" name="edit-date" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="edit-deli-cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
