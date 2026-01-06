<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With");
header('Content-Type: text/html; charset=UTF-8');
include_once("../../database/connect.php");
$data = json_decode(file_get_contents("php://input"));
$id   = $data -> id;
$sql = "select * from lesson where lesson_id = '$id'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['video'];
    echo $row['lesson_content'];}
 ?>