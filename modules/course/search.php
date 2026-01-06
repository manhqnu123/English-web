<?php 
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With");
header('Content-Type: text/html; charset=UTF-8');

require_once 'C://xamppserver//htdocs//English//database//connect.php';
$data = json_decode(file_get_contents("php://input"));
$keyword = $data -> keyword;
$search_sql = "SELECT course_id, course_name, price 
            FROM course 
            WHERE course_name LIKE '%$keyword%'
            OR description LIKE '%$keyword%' ";
$result = mysqli_query($conn, $search_sql);
if($result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {
    echo '<a href="http://localhost:8080/English/?module=course&action=learning&id='.$row['course_id'].'"><div class="result-item">
        <img src="https://i.pinimg.com/1200x/c5/e5/48/c5e548cd39d6014c8e2f5f5c0ea1bad0.jpg" alt="Product">
        <div class="result-content">
            <strong>'.$row['course_name'].'</strong><br>
            <span>Giá: '.$row['price'].'</span>
        </div>
    </div></a>';
    } 
}else {
    echo '<strong>Không tìm thấy khóa học</strong>';
}
 ?>