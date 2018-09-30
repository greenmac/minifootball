<?php

session_start();
if (!empty($_SESSION['manager_name'])) {
    $user = $_SESSION['manager_name'];
} else {
    header('Location:login.php');
    exit();
}
