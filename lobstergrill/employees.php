<?php
include('connect.php');
include('verified.php');

function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-employee'])) {
    $firstName = sanitizeInput($_POST['first-name']);
    $lastName = sanitizeInput($_POST['last-name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $role = sanitizeInput($_POST['role']);
    $password = sanitizeInput($_POST['password']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $uploadDir = 'employeeProfile/';
    $uploadedFile = $uploadDir . basename($_FILES['profile']['name']);
    if (move_uploaded_file($_FILES['profile']['tmp_name'], $uploadedFile)) {
        $stmt = $conn->prepare("INSERT INTO users (icon_path, user_fname, user_lname, email, role_id, user_password) VALUES (?, ?, ?, ?, (SELECT role_id FROM role WHERE role_name = ?), ?)");
        $stmt->bind_param("ssssss", $uploadedFile, $firstName, $lastName, $email, $role, $hashed_password);
        if ($stmt->execute()) {
            echo "Employee added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to upload profile image.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit-employee'])) {
    $userId = $_POST['user-id'];
    $firstName = sanitizeInput($_POST['first-name']);
    $lastName = sanitizeInput($_POST['last-name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $role = sanitizeInput($_POST['role']);

    if (!empty($_FILES['profile']['name'])) {
        $uploadDir = 'employeeProfile/';
        $uploadedFile = $uploadDir . basename($_FILES['profile']['name']);
        if (move_uploaded_file($_FILES['profile']['tmp_name'], $uploadedFile)) {
            $stmt = $conn->prepare("UPDATE users SET user_fname=?, user_lname=?, email=?, role_id=(SELECT role_id FROM role WHERE role_name=?), icon_path=? WHERE user_id=?");
            $stmt->bind_param("sssssi", $firstName, $lastName, $email, $role, $uploadedFile, $userId);
            if ($stmt->execute()) {
                echo "Employee updated successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to upload profile image.";
        }
    } else {
        $stmt = $conn->prepare("UPDATE users SET user_fname=?, user_lname=?, email=?, role_id=(SELECT role_id FROM role WHERE role_name=?) WHERE user_id=?");
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $role, $userId);
        if ($stmt->execute()) {
            echo "Employee updated successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_id=?");
        $stmt->bind_param("si", $password, $userId);
        if ($stmt->execute()) {
            echo "Password updated successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

if (isset($_GET['delete-employee'])) {
    $userId = $_GET['user-id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        echo "Employee deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$result = mysqli_query($conn, "SELECT u.user_id, u.user_fname, u.user_lname, u.email, r.role_name AS role, u.icon_path FROM users u JOIN role r ON u.role_id = r.role_id");
$employees = mysqli_fetch_all($result, MYSQLI_ASSOC);

$role_result = mysqli_query($conn, "SELECT role_name FROM role");
$roles = mysqli_fetch_all($role_result, MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobster Grill Employees</title>
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
                        <a href="supplier.php"><i class="fas fa-truck"></i>Suppliers</a>
                    <?php endif; ?>
                    <?php if ($role_name === 'Owner'): ?>
                        <a href="reports.php"><i class="fas fa-chart-line"></i>Reports</a>
                    <?php endif; ?>
                    <?php if ($role_name === 'Owner'): ?>
                        <a href="employees.php" class="active"><i class="fas fa-users"></i>Employees</a>
                    <?php endif; ?>
                    <a href="logout.php" class="sign-out"><i class="fas fa-sign-out-alt"></i>Sign out</a>
                </nav>
            </div>
        </aside>
        <main class="main">
            <header class="header">
                <h2>Employees</h2>
                <div class="controls">
                    <button class="btn btn-primary" id="emp-add-new-btn">Add New</button>
                </div>
            </header>
            <div class="container">
                <div class="content">
                    <section class="employees">
                        <div class="search-bar">
                            <input type="text" placeholder="Search..">
                        </div>
                        <table class="employee-table">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee): ?>
                                    <tr data-id="<?php echo $employee['user_id']; ?>">
                                        <td><?php echo $employee['user_fname'] . ' ' . $employee['user_lname']; ?></td>
                                        <td><?php echo $employee['role']; ?></td>
                                        <td><?php echo $employee['email']; ?></td>
                                        <td><button class="emp-edit-btn" data-id="<?php echo $employee['user_id']; ?>" 
                                        data-fname="<?php echo $employee['user_fname']; ?>" 
                                        data-lname="<?php echo $employee['user_lname']; ?>" 
                                        data-email="<?php echo $employee['email']; ?>" 
                                        data-role="<?php echo $employee['role']; ?>" 
                                        data-icon="<?php echo $employee['icon_path']; ?>">
                                        <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="emp-delete-btn" onclick="deleteEmployee(<?php echo $employee['user_id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                        </button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Employee Modal -->
    <div id="employee-modal" class="modal">
        <div class="modal-content">
            <h2>Add Employee</h2>
            <form id="employee-form" method="POST" action="employees.php" enctype="multipart/form-data">
                <div class="profile-icon-container">
                    <div class="profile-icon" id="profile-icon">
                        <img src="employeeProfile/default.png" alt="Profile Icon">
                    </div>  
                    <input type="file" id="profile-icon-upload" name="profile" accept="image/*" style="display: none;">
                    <button type="button" class="upload-btn" onclick="document.getElementById('profile-icon-upload').click();">Upload Image</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first-name">First Name</label>
                        <input type="text" id="first-name" name="first-name" required>
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" id="last-name" name="last-name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['role_name']; ?>"><?php echo $role['role_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="emp-cancel-btn">Cancel</button>
                    <button type="submit" name="add-employee" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div id="employee-edit-modal" class="modal">
        <div class="modal-content">
            <h2>Edit Employee</h2>
            <form id="employee-edit-form" method="POST" action="employees.php" enctype="multipart/form-data">
                <input type="hidden" id="user-id" name="user-id">
                <div class="profile-icon-container">
                    <div class="profile-icon" id="profile-icon-edit">
                        <img src="employeeProfile/default.png" alt="Profile Icon">
                    </div>
                    <input type="file" id="profile-icon-upload-edit" name="profile" accept="image/*" style="display: none;">
                    <button type="button" class="upload-btn" onclick="document.getElementById('profile-icon-upload-edit').click();">Upload Image</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-first-name">First Name</label>
                        <input type="text" id="edit-first-name" name="first-name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-last-name">Last Name</label>
                        <input type="text" id="edit-last-name" name="last-name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-email">Email</label>
                        <input type="email" id="edit-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-role">Role</label>
                        <select id="edit-role" name="role" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['role_name']; ?>"><?php echo $role['role_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="edit-password">Password</label>
                        <input type="password" id="edit-password" name="password">
                    </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="edit-cancel-btn">Cancel</button>
                    <button type="submit" name="edit-employee" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
