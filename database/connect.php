<?php
// Thông tin kết nối
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "english_2";

$conn = new mysqli($servername, $username, $password, $dbname);

function checkCourseAccess($user_id, $course_id, $conn) {
    // Kiểm tra xem user đã thanh toán khóa học chưa
    $sql = "SELECT * FROM usercourse
            WHERE user_id = $user_id 
            AND course_id = $course_id 
            AND payment_status = 'paid'";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return true;
    }
    
    // Kiểm tra khóa học miễn phí
    $course_sql = "SELECT price FROM course WHERE course_id = $course_id";
    $course_result = mysqli_query($conn, $course_sql);
    $course = mysqli_fetch_assoc($course_result);
    
    if ($course['price'] == 0) {
        return true;
    }
    
    return false;
}
?>