<?php
include('connect.php');
include('verified.php');

// Function to fetch results
function fetchResults($conn, $query) {
    $results = mysqli_query($conn, $query);
    $items = [];
    while ($result = mysqli_fetch_assoc($results)) {
        $items[] = $result;
    }
    return $items;
}

// Function to get total count based on the query
function getTotalCount($conn, $condition = '') {
    $totalQuery = "SELECT COUNT(*) AS total FROM inventory_pack WHERE active_status='Active' $condition";
    $totalResult = mysqli_query($conn, $totalQuery);
    $totalRow = mysqli_fetch_assoc($totalResult);
    return $totalRow['total'];
}

// Initialize variables
$limit = 7; // Number of entries to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = max(0, ($page - 1) * $limit);
$total = 0;
$pages = 0;
$items = [];
$categories = [];

// Fetch unique categories
$categoryQuery = "SELECT DISTINCT category FROM inventory_pack WHERE active_status='Active'";
$categoryResults = mysqli_query($conn, $categoryQuery);
while ($category = mysqli_fetch_assoc($categoryResults)) {
    $categories[] = $category['category'];
}

// Handle AJAX requests for search and filter
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search'])) {
        $searchTerm = htmlspecialchars($_POST['search']);
        $query = "SELECT inventory_pack_id, name, category, price, weight, minquantity 
                  FROM inventory_pack 
                  WHERE active_status='Active' AND name LIKE '%$searchTerm%'";

        $items = fetchResults($conn, $query);
        header('Content-Type: application/json');
        echo json_encode(['items' => $items]);
        exit();
    }

    if (isset($_POST['filter-category'])) {
        $filterCategory = htmlspecialchars($_POST['filter-category']);
        $query = "SELECT inventory_pack_id, name, category, price, weight, minquantity 
                  FROM inventory_pack 
                  WHERE active_status='Active'";
        if ($filterCategory !== '') {
            $query .= " AND category = '$filterCategory'";
        }

        $items = fetchResults($conn, $query);
        header('Content-Type: application/json');
        echo json_encode(['items' => $items]);
        exit();
    }
}


// Default query to fetch items
$total = getTotalCount($conn);
$pages = ceil($total / $limit);

$query = "SELECT inventory_pack_id, name, category, price, weight, minquantity 
          FROM inventory_pack 
          WHERE active_status='Active' 
          LIMIT $start, $limit";
$results = fetchResults($conn, $query);

// Handle dish edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit-dish-name'])) {
    $editId = htmlspecialchars($_POST['edit-dish-id']);
    $originalDishName = htmlspecialchars($_POST['original-dish-name']);
    $editName = htmlspecialchars($_POST['edit-dish-name']);
    $editCategory = htmlspecialchars($_POST['edit-dish-category']);
    $editPrice = htmlspecialchars($_POST['edit-dish-price']);
    $editWeight = htmlspecialchars($_POST['edit-dish-weight']);
    $editMinQuantity = htmlspecialchars($_POST['edit-dish-minquantity']);

    if ($editCategory == "NewCategory") {
        $editCategory = htmlspecialchars($_POST['edit-new-category-box']);
    }

    $compareQuery = "SELECT LOWER(name) AS name FROM inventory_pack WHERE name = LOWER('$editName') AND name != '$originalDishName'";
    $compareResult = mysqli_query($conn, $compareQuery);
    if (mysqli_num_rows($compareResult) == 0) {
        $editQuery = "UPDATE inventory_pack SET name='$editName', category='$editCategory', price='$editPrice', weight='$editWeight', minquantity='$editMinQuantity' WHERE inventory_pack_id='$editId'";
        mysqli_query($conn, $editQuery);
        echo "<script>alert('Item updated successfully');</script>";
    } else {
        echo "<script>alert('[ERROR] $editName already exists! Action cancelled.');</script>";
    }
}

// Handle dish delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete-dish-id'])) {
    $deleteId = htmlspecialchars($_POST['delete-dish-id']);
    $deleteQuery = "UPDATE inventory_pack SET active_status='Inactive' WHERE inventory_pack_id='$deleteId'";
    
    header('Content-Type: application/json');
    if (mysqli_query($conn, $deleteQuery)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit();
}

// Handle adding a new dish
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-dish-name'])) {
    $name = htmlspecialchars($_POST['add-dish-name']);
    $category = htmlspecialchars($_POST['add-dish-category']);
    $price = htmlspecialchars($_POST['add-dish-price']);
    $weight = htmlspecialchars($_POST['add-dish-weight']);
    $minquantity = htmlspecialchars($_POST['add-dish-minquantity']);

    if ($category == "NewCategory") {
        $category = htmlspecialchars($_POST['new-category-box']);
    }

    $compareQuery = "SELECT LOWER(name) AS name FROM inventory_pack WHERE name = LOWER('$name') AND active_status = 'Active';";
    $compareResult = mysqli_query($conn, $compareQuery);
    $insertQuery = "INSERT INTO inventory_pack (name, quantity, minquantity, category, weight, price, status, active_status)
                    VALUES ('$name', 0, '$minquantity', '$category', '$weight', '$price', 'Available', 'Active')";
    if (mysqli_num_rows($compareResult) == 0) {
        if (mysqli_query($conn, $insertQuery)) {
            echo "<script>
                alert('Item added successfully');
                window.location.href = 'inventory.php';
            </script>";
        } else {
            echo "<script>alert('Error adding item: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('[ERROR] $name already exists! Action cancelled.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobster Grill Inventory</title>
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
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Sign out</a>
            </nav>
            </div>
        </aside>
        
        <main class="main">
            <header class="header">
                <h2>Inventory</h2>
                <div class="controls">
                    <div class="filter-container">
                        <i class="fas fa-filter filter-icon"></i>
                        <select id="filter-category" class="filter-category-btn">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($role_name === 'Owner'): ?><button class="btn btn-primary" id="add-new-dish-btn">New Dish</button><?php endif; ?>
                </div>
            </header>

            <div class="container">
                <div class="content">
                    <section class="inventory">
                        <div class="search-bar">
                            <input type="text" id="search-bar" placeholder="Search..">
                        </div>
                        <table class="inventory-table">
                            <thead>
                                <tr>
                                    <th>Dish Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Weight (Kg)</th>
                                    <th>Min Quantity</th>
                                    <th>Inventory</th>
                                    <?php if ($role_name === 'Owner'): ?>
                                    <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($results as $result): ?>
                                    <tr data-id="<?php echo $result['inventory_pack_id']; ?>">
                                        <td><?php echo $result['name']; ?></td>
                                        <td><?php echo $result['category']; ?></td>
                                        <td><?php echo $result['price']; ?></td>
                                        <td><?php echo $result['weight']; ?></td>
                                        <td><?php echo $result['minquantity']; ?></td>
                                        <td class="view">
                                            <form action="dishRecord.php" method="GET">
                                                <input type="hidden" name="dish" value="<?php echo $result['inventory_pack_id']; ?>">
                                                <button type="submit" class="btn btn-primary">View Inventory</button>
                                            </form>
                                        </td>
                                        <td>
                                        <?php if ($role_name === 'Owner'): ?><button class="inv-edit-btn"
                                                data-id="<?php echo $result['inventory_pack_id']; ?>"
                                                data-name="<?php echo $result['name']; ?>"
                                                data-category="<?php echo $result['category']; ?>"
                                                data-price="<?php echo $result['price']; ?>"
                                                data-weight="<?php echo $result['weight']; ?>"
                                                data-minquantity="<?php echo $result['minquantity']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="inv-delete-btn"
                                                data-id="<?php echo $result['inventory_pack_id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if ($pages > 1): ?>
                        <div class="pagination">
                            <?php if($page > 1): ?>
                                <a href="inventory.php?page=<?php echo $page - 1; ?>" class="pagination-link">Previous</a>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $pages; $i++): ?>
                                <a href="inventory.php?page=<?php echo $i; ?>" class="pagination-link <?php if($page == $i) echo 'active'; ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>

                            <?php if($page < $pages): ?>
                                <a href="inventory.php?page=<?php echo $page + 1; ?>" class="pagination-link">Next</a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
            <!-- Add Dish Modal -->
            <div id="add-dish-modal" class="modal">
                <div class="modal-content">
                    <h2>Add New Dish</h2>
                    <form id="add-dish-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="form-group">
                            <label for="add-dish-name">Dish Name</label>
                            <input type="text" id="add-dish-name" name="add-dish-name" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="add-dish-category">Category</label>
                                <select id="add-dish-category" name="add-dish-category" required>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                                    <?php endforeach; ?>
                                    <option value="NewCategory">New Category:</option>
                                </select>
                                <div class="form-group" style="display:none" id="new-category-box">
                                    <label for="new-category-box">Enter new category</label>
                                    <input type="text" name="new-category-box">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add-dish-price">Price</label>
                                <input type="number" step="0.01" min="0" id="add-dish-price" name="add-dish-price" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="add-dish-weight">Weight (in Kg)</label>
                                <input type="number" step="0.01" min="0" id="add-dish-weight" name="add-dish-weight" required>
                            </div>
                            <div class="form-group">
                                <label for="add-dish-minquantity">Minimum Stock Quantity</label>
                                <input type="number" min="0" id="add-dish-minquantity" name="add-dish-minquantity" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-cancel" id="add-dish-cancel-btn">Cancel</button>
                            <button type="submit" name="add-dish" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Dish Modal -->
            <div id="edit-dish-modal" class="modal">
                <div class="modal-content">
                    <h2>Edit Dish</h2>
                    <form id="edit-dish-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" id="edit-dish-id" name="edit-dish-id">
                        <input type="hidden" id="original-dish-name" name="original-dish-name">
                        <div class="form-group">
                            <label for="edit-dish-name">Dish Name</label>
                            <input type="text" id="edit-dish-name" name="edit-dish-name" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit-dish-category">Category</label>
                                <select id="edit-dish-category" name="edit-dish-category" required>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                                    <?php endforeach; ?>
                                    <option value="NewCategory">New Category:</option>
                                </select>
                                <div class="form-group" style="display:none" id="edit-new-category-box">
                                    <label for="edit-new-category-box">Enter new category</label>
                                    <input type="text" name="edit-new-category-box">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-dish-price">Price</label>
                                <input type="number" step="0.01" min="0" id="edit-dish-price" name="edit-dish-price" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit-dish-weight">Weight (in Kg)</label>
                                <input type="number" step="0.01" min="0" id="edit-dish-weight" name="edit-dish-weight" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-dish-minquantity">Minimum Stock Quantity</label>
                                <input type="number" min="0" id="edit-dish-minquantity" name="edit-dish-minquantity" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-cancel" id="edit-dish-cancel-btn">Cancel</button>
                            <button type="submit" name="edit-dish" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Dish Form -->
            <form id="delete-dish-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" style="display:none;">
                <input type="hidden" id="delete-dish-id" name="delete-dish-id">
            </form>

        </main>
    </div>
<script src="script.js"></script>
</body>
</html>
