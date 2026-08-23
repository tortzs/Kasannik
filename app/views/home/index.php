<?php
//session_start(); # we create session in public/index.php already
if (Auth::check()) {
    header("Location: /dashboard");
    exit();
} else {
    header("Location: /login");
    exit();
}
?>