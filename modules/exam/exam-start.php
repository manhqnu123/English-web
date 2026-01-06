<div class="container">
    <?php
        require_once './database/connect.php';
        require_once './templates/sidebar.php';
        require_once './templates/header.php';
    ?>
</div>

<?php 
if(isset($_GET['id'])) {
    $exam_id = $_GET['id'];
    $sql_exam = "select exam_content, duration, type from exam where exam_id = $exam_id";
    $result_exam = mysqli_query($conn, $sql_exam);
    $exam = mysqli_fetch_array($result_exam);
    
    // Xác định class dựa trên type
    $main_class = 'main-question';
    if ($exam['type'] == 'grammar') {
        $main_class = 'main-question--grammar';
    }
    
    $duration_minutes = $exam['duration'];
    $duration_time = $exam['duration']; // "00:20:00"
    // Tách thành giờ, phút, giây
    list($hours, $minutes, $seconds) = explode(':', $duration_time);
    // Tính tổng số phút
    $duration_minutes = ($hours * 60) + $minutes + ($seconds / 60);
    // Hoặc tính tổng số giây
    $duration_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
?>

<!-- Sử dụng biến $main_class động -->
<div class="<?php echo $main_class; ?>">
    <?php
    if ($exam['type'] == 'reading') {
        echo '<p class="exam-title">'.$exam['exam_content'].'</p>';
    }
    
    $sql = "select exam.exam_id, question.content as question_content, question.option_text, question.type, question.order_number, question.question_id from exam left join question on exam.exam_id = question.exam_id where exam.exam_id = $exam_id";
    $result = mysqli_query($conn, $sql);
    
    echo '<div class="question-list">';
    while ($row = mysqli_fetch_array($result)) {
        if($row['type'] == 'select') {
            echo '
                <div class="question-item" id="'.$row['question_id'].'">
                    <h4 class="question-content">'.$row['order_number'].'.  '.$row['question_content'].'</h4>
                    '.$row['option_text'].'
                </div>
            ';
        } else {
            echo '<div class="question-item display-flex" id="'.$row['question_id'].'">
                    '.$row['order_number'].'
                    <input type="text" class="answer-text"/>
                </div>';
        }
    }
    echo '</div>';
    
    echo '<div class="exam-end">
            <p id="timerDisplay">Thời gian làm bài: '.$exam['duration'].'</p>
            <button class="submit">Nộp bài</button>
        </div>';
    ?>
</div>

<?php } ?>

<script type="text/javascript">
let duration = <?php echo $duration_seconds; ?>;
let totalDuration = duration;
let timerInterval;

$('button').click(function(e) {
    e.preventDefault();
    let timeSpent = totalDuration - duration;
    let hours = Math.floor(timeSpent / 3600);
    let minutes = Math.floor((timeSpent % 3600) / 60);
    let seconds = timeSpent % 60;
    let timeSpentFormatted = String(hours).padStart(2, '0') + ':' +
        String(minutes).padStart(2, '0') + ':' +
        String(seconds).padStart(2, '0');

    let ans_text = $('.question-item input[type="text"]');
    let ans_select = $('.question-item input[type="radio"]:checked');
    let id_exam = <?php echo $_GET['id']; ?>;
    let answers = [];

    let ans_func = function() {
        let ans_user = {
            id: parseInt($(this).parents('.question-item').attr('id')),
            value: $(this).val()
        }
        answers.push(ans_user);
    }

    ans_select.each(ans_func);
    ans_text.each(ans_func);

    $.ajax({
        url: "http://localhost:8080/English/modules/exam/exam-post.php",
        method: "post",
        contentType: "application/json",
        data: JSON.stringify({
            ans_user: answers,
            id: id_exam,
            time_spent: timeSpentFormatted // Gửi thời gian đã làm bài
        })
    }).done(function(result) {
        clearInterval(timerInterval);
        $(".main-question, .main-question--grammar").html(result);
    })
});

function updateTimer() {
    if (duration <= 0) {
        clearInterval(timerInterval);
        alert("Hết thời gian");
        $(".submit").click();
        return;
    }

    let hours = Math.floor(duration / 3600);
    let minutes = Math.floor((duration % 3600) / 60);
    let seconds = duration % 60;

    let timeString = String(hours).padStart(2, '0') + ':' +
        String(minutes).padStart(2, '0') + ':' +
        String(seconds).padStart(2, '0');

    $('#timerDisplay').text('Thời gian còn lại: ' + timeString);
    duration--;
}

updateTimer();
timerInterval = setInterval(updateTimer, 1000);
</script>