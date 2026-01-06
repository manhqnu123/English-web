<?php
session_start();
$user_id = $_SESSION['user']['user_id'];
$minutes = isset($_POST['minutes']) ? intval($_POST['minutes']) : 0;

if ($minutes > 0) {
    require_once "C://xamppserver//htdocs//English//database//connect.php";
    
    $sql = "INSERT INTO user_study_total (user_id, total_minutes) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE total_minutes = total_minutes + ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $minutes, $minutes);
    $stmt->execute();
    
    echo "Study time updated successfully.";
}
?>