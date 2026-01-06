<?php 
    require_once './database/connect.php';
    
    if (isset($_GET['partnerCode'])) {
        $order_id = $_GET['orderId'];
        $parts = explode("_", $order_id);
        $course_id = $parts[1];
        $user_id = $_SESSION['user']['user_id'];
        $amount = $_GET['amount'];
        // echo $course_id;
        $insert_payment = "insert into usercourse (user_id, course_id, amount, payment_status, enrollment_date)
                        values ('$user_id', '$course_id','$amount', 'paid', NOW())";
        if(mysqli_query($conn, $insert_payment)) {
            header("Location: http://localhost:8080/English/");
        } else {
            echo "Đăng ký khóa học thất bại!";
        }
    }
?>