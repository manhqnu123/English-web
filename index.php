<?php
    session_start();
    define('ACCESS_ALLOWED', true);
    $module = 'home';
    $action = 'dashboard';

    if (isset($_GET['module'])) {
        $module = $_GET['module'];
    }
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    }
    $file = 'modules/' . $module . '/' . $action . '.php';
    
    if (file_exists($file)) {
        include $file;
    } else {
        echo "Error: The requested page does not exist.";
    }
 ?>