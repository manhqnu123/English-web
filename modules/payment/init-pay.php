<?php
header('Content-type: text/html; charset=utf-8');
require_once 'C://xamppserver//htdocs//English//database//connect.php';

// ==================== HÀM XỬ LÝ MOMO ====================
function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// ==================== CẤU HÌNH MOMO ====================
$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
$redirectUrl = "http://localhost:8080/English/?module=payment&action=payment";
$ipnUrl = "http://localhost:8080/English/?module=payment&action=payment";

// ==================== LẤY THÔNG TIN TỪ DATABASE ====================
$course_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
$enrollment_id = isset($_GET['enrollment_id']) ? intval($_GET['enrollment_id']) : null;

$course = null;
$orderInfo = "Thanh toán qua MoMo";
$amount = 10000;
$orderId = time() . "";

if ($course_id) {
    // Lấy thông tin khóa học theo ID
    $stmt = $conn->prepare("SELECT * FROM course WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    $stmt->close();
    
    if ($course) {
        // Lấy thông tin từ database
        $orderInfo = isset($course['description']) && !empty($course['description']) 
                     ? $course['description'] 
                     : (isset($course['course_name']) ? "Thanh toán khóa học " . $course['course_name'] : "Thanh toán khóa học");
        
        $amount = isset($course['price']) && $course['price'] > 0 
                  ? $course['price'] 
                  : 10000;
        
        $orderId = time() . "_" . $course['course_id'];
        
        // Nếu có enrollment_id, lấy thông tin từ usercourse
        if ($enrollment_id) {
            $stmt = $conn->prepare("SELECT * FROM usercourse WHERE user_id = ? AND course_id = ?");
            $stmt->bind_param("ii", $user_id, $course_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $enrollment = $result->fetch_assoc();
            $stmt->close();
            
            if ($enrollment) {
                // Nếu đã có enrollment, lấy amount từ đó
                if (isset($enrollment['amount']) && $enrollment['amount'] > 0) {
                    $amount = $enrollment['amount'];
                }
            }
        }
    } else {
        echo "<script>alert('Không tìm thấy khóa học!'); window.location.href='../../index.php';</script>";
        exit();
    }
}

// ==================== XỬ LÝ THANH TOÁN ====================
if (!empty($_POST)) {
    $orderId = $_POST["orderId"];
    $orderInfo = $_POST["orderInfo"];
    $amount = $_POST["amount"];

    $requestId = time() . "";
    $requestType = "payWithATM";
    $extraData = "";
    
    $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . 
               "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . 
               "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . 
               $requestId . "&requestType=" . $requestType;
    
    $signature = hash_hmac("sha256", $rawHash, $secretKey);
    
    $data = array(
        'partnerCode' => $partnerCode,
        'partnerName' => "Test",
        "storeId" => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature
    );
    
    $result = execPostRequest($endpoint, json_encode($data));
    $jsonResult = json_decode($result, true);

    if (isset($jsonResult['payUrl'])) {
        // Lưu hoặc cập nhật thông tin vào bảng usercourse
        if ($course_id && $user_id) {
            // Kiểm tra xem đã có enrollment chưa
            $check = $conn->prepare("SELECT * FROM usercourse WHERE user_id = ? AND course_id = ?");
            $check->bind_param("ii", $user_id, $course_id);
            $check->execute();
            $result = $check->get_result();
            $existing = $result->fetch_assoc();
            $check->close();
            
            if ($existing) {
                // Cập nhật enrollment hiện tại
                $stmt = $conn->prepare("UPDATE usercourse SET 
                    amount = ?,
                    payment_status = 'pending',
                    momo_order_id = ?
                    WHERE user_id = ? AND course_id = ?
                ");
                $stmt->bind_param("isii", $amount, $orderId, $user_id, $course_id);
                $stmt->execute();
                $stmt->close();
            } else {
                // Tạo enrollment mới
                $stmt = $conn->prepare("INSERT INTO usercourse 
                    (user_id, course_id, enrollment_date, purchase_type, amount, payment_status, momo_order_id) 
                    VALUES (?, ?, NOW(), 'online', ?, 'pending', ?)
                ");
                $stmt->bind_param("iiis", $user_id, $course_id, $amount, $orderId);
                $stmt->execute();
                $stmt->close();
            }
        }
        
        header('Location: ' . $jsonResult['payUrl']);
        exit();
    } else {
        // Xử lý lỗi
        $error_message = isset($jsonResult['message']) ? $jsonResult['message'] : 'Có lỗi xảy ra';
        echo "<script>alert('Lỗi: $error_message');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán MoMo</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #f5f5f5;
        padding: 20px;
    }

    .container {
        max-width: 600px;
        margin: 40px auto;
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .header-icon {
        font-size: 24px;
    }

    .header h2 {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }

    .form-row {
        margin-bottom: 20px;
    }

    .form-row-double {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    label .required {
        color: #f44336;
    }

    input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.2s;
        background: white;
    }

    input:focus {
        outline: none;
        border-color: #A50064;
        box-shadow: 0 0 0 3px rgba(165, 0, 100, 0.1);
    }

    input:read-only {
        background: #f8f8f8;
        color: #666;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: #A50064;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 8px;
    }

    .btn-submit:hover {
        background: #8a0054;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(165, 0, 100, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .info-badge {
        background: #e3f2fd;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #1976d2;
    }

    .course-info {
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .course-info h3 {
        font-size: 16px;
        color: #333;
        margin-bottom: 8px;
    }

    .course-info p {
        font-size: 14px;
        color: #666;
        margin: 4px 0;
    }

    .course-info .price {
        font-size: 18px;
        color: #A50064;
        font-weight: 600;
        margin-top: 8px;
    }

    @media (max-width: 640px) {
        .form-row-double {
            grid-template-columns: 1fr;
        }

        .container {
            padding: 20px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <span class="header-icon">📄</span>
            <h2>Thông tin đơn hàng</h2>
        </div>

        <?php if ($course_id && $course): ?>


        <div class="course-info">
            <h3>📚 <?php echo htmlspecialchars($course['course_name'] ?? 'Khóa học'); ?></h3>
            <?php if (isset($course['description']) && !empty($course['description'])): ?>
            <p><?php echo htmlspecialchars($course['description']); ?></p>
            <?php endif; ?>
            <p class="price">💰 <?php echo number_format($amount); ?> VNĐ</p>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="partnerCode" value="<?php echo $partnerCode; ?>">
            <input type="hidden" name="accessKey" value="<?php echo $accessKey; ?>">
            <input type="hidden" name="secretKey" value="<?php echo $secretKey; ?>">
            <input type="hidden" name="redirectUrl" value="<?php echo $redirectUrl; ?>">
            <input type="hidden" name="ipnUrl" value="<?php echo $ipnUrl; ?>">

            <div class="form-row-double">
                <div>
                    <label for="orderId">Order ID (Tự động tạo)</label>
                    <input type="text" name="orderId" id="orderId" value="<?php echo $orderId; ?>"
                        readonly>
                </div>

                <div>
                    <label for="amount">Số tiền (VNĐ) <span class="required">*</span></label>
                    <input type="number" name="amount" id="amount" value="<?php echo $amount; ?>"
                        required min="1000" step="1000">
                </div>
            </div>

            <div class="form-row">
                <label for="orderInfo">Thông tin đơn hàng <span class="required">*</span></label>
                <input type="text" name="orderInfo" id="orderInfo"
                    value="<?php echo htmlspecialchars($orderInfo); ?>" required>
            </div>

            <button type="submit" class="btn-submit">
                💳 Thanh toán với MoMo
            </button>
        </form>
    </div>
</body>

</html>