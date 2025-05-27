<?php
include('connect.php');
include('verified.php');

// Handle form submission to add a new supplier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-supplier'])) {
    $supplierName = htmlspecialchars($_POST['supplier-name']);
    $contactNumber = htmlspecialchars($_POST['contact-number']);
    $street = htmlspecialchars($_POST['street']);
    $city = htmlspecialchars($_POST['city']);
    $province = htmlspecialchars($_POST['province']);

    $stmt = $conn->prepare("INSERT INTO suppliers (supplier_name, contact_number, street, City, Province) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $supplierName, $contactNumber, $street, $city, $province);
    if ($stmt->execute()) {
        echo "Supplier added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle form submission to edit an existing supplier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit-supplier'])) {
    $supplierId = htmlspecialchars($_POST['supplier-id']);
    $supplierName = htmlspecialchars($_POST['supplier-name']);
    $contactNumber = htmlspecialchars($_POST['contact-number']);
    $street = htmlspecialchars($_POST['street']);
    $city = htmlspecialchars($_POST['city']);
    $province = htmlspecialchars($_POST['province']);

    $stmt = $conn->prepare("UPDATE suppliers SET supplier_name=?, contact_number=?, street=?, City=?, Province=? WHERE supplier_id=?");
    $stmt->bind_param("sssssi", $supplierName, $contactNumber, $street, $city, $province, $supplierId);
    if ($stmt->execute()) {
        echo "Supplier updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch suppliers to display in the table
$result = mysqli_query($conn, "SELECT * FROM suppliers");
$suppliers = mysqli_fetch_all($result, MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobster Grill Suppliers</title>
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
                    <a href="inventory.php"><i class="fas fa-boxes"></i>Inventory</a>
                    <?php if ($role_name === 'Owner' || $role_name === 'Manager'): ?>
                        <a href="supplier.php" class="active"><i class="fas fa-truck"></i>Suppliers</a>
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
                <h2>Suppliers</h2>
                <div class="controls">
                <?php if ($role_name === 'Owner'): ?><button class="btn btn-primary" id="sup-add-new-btn">Add New</button><?php endif; ?>
                </div>
            </header>
            <div class="container">
                <div class="content">
                    <section class="suppliers">
                        <div class="search-bar">
                            <input type="text" placeholder="Search..">
                        </div>
                        <table class="suppliers-table">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Contact Number</th>
                                    <th>Address</th>
                                    <?php if ($role_name === 'Owner'): ?><th>Actions</th><?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <tr>
                                        <td><?php echo $supplier['supplier_name']; ?></td>
                                        <td><?php echo $supplier['contact_number']; ?></td>
                                        <td><?php echo $supplier['street'] . ', ' . $supplier['City'] . ', ' . $supplier['Province']; ?></td>
                                        <?php if ($role_name === 'Owner'): ?><td><button class="sup-edit-btn" 
                                        data-id="<?php echo $supplier['supplier_id']; ?>" 
                                        data-name="<?php echo $supplier['supplier_name']; ?>" 
                                        data-contact="<?php echo $supplier['contact_number']; ?>" 
                                        data-street="<?php echo $supplier['street']; ?>" 
                                        data-city="<?php echo $supplier['City']; ?>" 
                                        data-province="<?php echo $supplier['Province']; ?>">
                                        <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="sup-delete-btn">
                                        <i class="fas fa-trash"></i>
                                        </button></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Supplier Modal -->
    <div id="suppliers-modal" class="modal">
        <div class="modal-content">
            <h2>Add Supplier</h2>
            <form id="suppliers-form" method="POST" action="supplier.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="supplier-name">Supplier Name</label>
                        <input type="text" id="supplier-name" name="supplier-name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-number">Contact Number</label>
                        <input type="tel" id="contact-number" name="contact-number" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="street">Street</label>
                        <input type="text" id="street" name="street" required>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="province">Province</label>
                        <input type="text" id="province" name="province" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="sup-cancel-btn">Cancel</button>
                    <button type="submit" name="add-supplier" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Supplier Modal -->
    <div id="suppliers-edit-modal" class="modal">
        <div class="modal-content">
            <h2>Edit Supplier</h2>
            <form id="suppliers-edit-form" method="POST" action="supplier.php">
                <input type="hidden" id="supplier-id" name="supplier-id">
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-supplier-name">Supplier Name</label>
                        <input type="text" id="edit-supplier-name" name="supplier-name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-contact-number">Contact Number</label>
                        <input type="tel" id="edit-contact-number" name="contact-number" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-street">Street</label>
                        <input type="text" id="edit-street" name="street" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-city">City</label>
                        <input type="text" id="edit-city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-province">Province</label>
                        <input type="text" id="edit-province" name="province" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="edit-sup-cancel-btn">Cancel</button>
                    <button type="submit" name="edit-supplier" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
