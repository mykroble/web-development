<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_fname = $_SESSION['user_fname'];
$user_lname = $_SESSION['user_lname'];
$icon_path = $_SESSION['icon_path'];
$role_id = $_SESSION['role_id'];
$role_name = "";

$stmt = $conn->prepare("SELECT role_name FROM role WHERE role_id = ?");
$stmt->bind_param("i", $role_id);
$stmt->execute();
$stmt->bind_result($role_name);
$stmt->fetch();
$stmt->close();
?>
