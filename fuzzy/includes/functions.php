<?php
session_start();

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

function login($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $stored_password);
        $stmt->fetch();
        
        if ($password == $stored_password) {
            $_SESSION['user_id'] = $id;
            return true;
        }
    }
    return false;
}

function logout() {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
