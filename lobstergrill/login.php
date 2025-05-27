<?php
include "connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, user_fname, user_lname, email, icon_path, role_id, user_password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $user_fname, $user_lname, $email, $icon_path, $role_id, $hashed_password);
        $stmt->fetch();
        if (isset($password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_fname'] = $user_fname;
            $_SESSION['user_lname'] = $user_lname;
            $_SESSION['email'] = $email;
            $_SESSION['icon_path'] = $icon_path;
            $_SESSION['role_id'] = $role_id;
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with this email.";
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href='style.css?v=<?php echo time(); ?>'>
</head>
<body>
<div class="main-content backgroundLogin">
    <div class="login-container">
        <div class="login-brand">
            <img src="logo.png" alt="Lobster Grill Logo">
            <h1>LOBSTER GRILL</h1>
        </div>
        <hr class="line"></hr>
        <form action="login.php" method="POST" class="login-form">
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn" name="login">Login</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
