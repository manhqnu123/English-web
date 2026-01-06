<?php 
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With");
header('Content-Type: text/html; charset=UTF-8');

require_once 'C://xamppserver//htdocs//English//database//connect.php';
$data = json_decode(file_get_contents("php://input"));
$keyword = $data -> keyword;
$search_sql = "SELECT user_id, fullname, role, avt,created_at 
            FROM user 
            WHERE fullname LIKE '%$keyword%'
            AND role ='học viên'";
$result = mysqli_query($conn, $search_sql);
if($result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {
    echo '<div class="manage-group-user">
                <img src="modules/uploads/'.$row['avt'].'">
                <div class="group-user-info">
                    <strong>'.$row['fullname'].'</strong>
                    <p>Join At: '.$row['created_at'].'</p>
                </div>
            </div>
            <div class="manage-group-btn">
                <a href="http://localhost:8080/English/?module=admin&action=view-student&id='.$row['user_id'].'"><"><button class="btn-view">Xem</button></a>
                <button class="btn-delete" data-id="'.$row['user_id'].'">Xóa</button>
            </div>';
    } 
}else {
    echo '<strong>Không tìm thấy học viên</strong>';
}
 ?>