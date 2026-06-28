<?php
session_start();
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
    header("Location: /dashboard");
    exit();
} else {
    header("Location: /login");
    exit();
}
?>