<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With");
header('Content-Type: text/html; charset=UTF-8');
require_once 'C://xamppserver//htdocs//English//database//connect.php';

$data = json_decode(file_get_contents("php://input"));
$id = $data->id;

$sqlCurrent = "SELECT * FROM user WHERE user_id = ?";
$stmtCurrent = $conn->prepare($sqlCurrent);
$stmtCurrent->bind_param("i", $id); 
$stmtCurrent->execute();
$result = $stmtCurrent->get_result();
$currentUser = $result->fetch_assoc();  

$updateFields = [];
$params = [];
$types = ""; 


if (!empty($data->fullname) && $data->fullname !== $currentUser['fullname']) {
    $updateFields[] = "fullname = ?";
    $params[] = $data->fullname;
    $types .= "s";
}

if (!empty($data->age) && $data->age != $currentUser['age']) {
    $updateFields[] = "age = ?";
    $params[] = $data->age;
    $types .= "i";
}

if (!empty($data->email) && $data->email !== $currentUser['email']) {
    $updateFields[] = "email = ?";
    $params[] = $data->email;
    $types .= "s";
}

if (!empty($data->phone) && $data->phone !== $currentUser['phone']) {
    $updateFields[] = "phone = ?";
    $params[] = $data->phone;
    $types .= "s";
}


if (!empty($data->pass)) {
    $updateFields[] = "password = ?";
    $params[] = md5($data->pass);
    $types .= "s";
}


if (!empty($data->image) && strpos($data->image, 'data:image') === 0) {
    try {
        $uploadDir = 'C://xamppserver//htdocs//English//modules//uploads//';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $imageData = explode(',', $data->image);
        if (count($imageData) !== 2) {
            throw new Exception('Định dạng ảnh không hợp lệ!');
        }
        
        preg_match('/data:image\/(.*?);base64/', $imageData[0], $matches);
        $extension = $matches[1] ?? 'png';
        
        $imageContent = base64_decode($imageData[1]);
        if ($imageContent === false) {
            throw new Exception('Không thể decode ảnh!');
        }
        
        if (strlen($imageContent) > 5 * 1024 * 1024) {
            throw new Exception('Kích thước ảnh không được vượt quá 5MB!');
        }
        
        $fileName = 'avatar_' . $id . '_' . time() . '.' . $extension;
        $fullPath = $uploadDir . $fileName;
        
        if (!file_put_contents($fullPath, $imageContent)) {
            throw new Exception('Không thể lưu ảnh!');
        }
        
      
        if (!empty($currentUser['avt']) && file_exists($uploadDir . $currentUser['avt'])) {
            unlink($uploadDir . $currentUser['avt']);
        }
        
        $updateFields[] = "avt = ?";
        $params[] = $fileName;
        $types .= "s";
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}


if (empty($updateFields)) {
    exit;
}


$params[] = $id;
$types .= "i";


$sql = "UPDATE user SET " . implode(', ', $updateFields) . " WHERE user_id = ?";
$stmt = $conn->prepare($sql);


$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo 'Cập nhật thành công';
} else {
    echo 'cập nhật thất bại';
}

$stmt->close();
$conn->close();
?>