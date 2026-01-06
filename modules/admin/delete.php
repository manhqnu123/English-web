<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With");
header('Content-Type: text/html; charset=UTF-8');

require_once 'C://xamppserver//htdocs//English//database//connect.php';
$data = json_decode(file_get_contents("php://input"));
$user_id = $data -> studentID;
$sql = "DELETE FROM user WHERE user_id = $user_id";
if ($conn->query($sql) === TRUE) {
        echo "Xóa học viên thành công.";
    } else {
        echo "Lỗi: " . $conn->error;
    }
?>