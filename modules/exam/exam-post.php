<?php
session_start();
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With");
header('Content-Type: text/html; charset=UTF-8');

require_once 'C://xamppserver//htdocs//English//database//connect.php';

$data = json_decode(file_get_contents("php://input"));
$ans_user = $data->ans_user;
$id_exam = $data->id;
$time_spent = $data->time_spent ?? '00:00:00';
$score = 0;
$id_user = $_SESSION['user']['user_id'];
$status = 'passed';
$total_questions = 0;

if(isset($ans_user)) {
    $answerSql = "SELECT question_id, correct FROM question WHERE exam_id = ?";
    $stmt = $conn->prepare($answerSql);
    $stmt->bind_param('i', $id_exam);
    $stmt->execute();
    $rs = $stmt->get_result();
    
    while($row = mysqli_fetch_array($rs)) {
        $total_questions++;
        foreach($ans_user as $item) {
            if ($item->id == $row['question_id'] && $item->value == $row['correct']) {
                $score += 1;
            }
        }
    }
    
    $score_per_question = 10 / $total_questions;
    $success_percent = round(($score / $total_questions) * 100, 2);
    $total_score = round($score * $score_per_question, 2);
    
    if($total_score < 5) {
        $status = 'failed';
    } else {
        $status = 'passed';
    }
    
    $insert_ans = "REPLACE INTO userexamresult 
                   (user_id, exam_id, score, complete_time, status) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_ans);
    $stmt->bind_param('iidss', $id_user, $id_exam, $total_score, $time_spent, $status);
    $stmt->execute();
    
    echo '<div class="result-container">
            <div class="result-header">
                <h1>Kết Quả Bài Thi</h1>
                <p class="user-info">'.$_SESSION['user']['fullname'].'</p>
                <div class="status-badge '.($status == 'passed' ? 'passed' : 'failed').'">'.$status.'</div>
            </div>

            <div class="score-circle">
                <div class="score-number">'.$score.'</div>
                <div class="score-text">'.$total_score.'/ 10 điểm</div>
            </div>

            <div class="result-details">
                <div class="detail-row">
                    <span class="detail-label">Số câu đúng</span>
                    <span class="detail-value">'.$score.' / '.$total_questions.'</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tỷ lệ chính xác</span>
                    <span class="detail-value">'.$success_percent.'%</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Thời gian hoàn thành</span>
                    <span class="detail-value">'.$time_spent.'</span>
                </div>
            </div>

            <div class="result-button-group">
                <a href="review.php?exam_id='.$id_exam.'" class="result-btn result-btn-secondary">Xem Chi Tiết</a>
                <a href="http://localhost:8080/English/?module=exam&action=exam-start&id='.$id_exam.'" class="result-btn result-btn-primary">Thi Lại</a>
            </div>
        </div>';
}
?>