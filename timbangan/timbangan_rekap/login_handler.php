<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['user_id'] = $_POST['user_id'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['role'] = $_POST['role'];
    
    header('Location: index.php');
    exit;
} else {
    header('Location: login.html');
    exit;
}
?>