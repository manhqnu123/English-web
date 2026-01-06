<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluentez</title>
</head>

<body>
    <div class="container">
        <?php
            require_once './database/connect.php';
            require_once './templates/sidebar.php';
            require_once './templates/header.php';
        ?>
    </div>
    <div class="main-learning">
        <?php
            $is_login = isset($_SESSION['user']);
            if (isset($_GET['id'])) {
                $course_id = $_GET['id'];
                $sql = "select * from lesson where course_id = '$course_id' LIMIT 1";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_array($result)) {
                     echo $row['video'];
                     echo $row['lesson_content'];
                }
            }
        ?>
    </div>
    <div class="menu-lesson">
        <?php if (isset($_GET['id'])) {
            $sql = "select lesson_id, lesson_title from lesson where course_id = '$course_id'";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
                echo '<div class="lesson-item" id="'.$row['lesson_id'].'">
                        <h4>'.$row['lesson_title'].'</h4>
                    </div>';
            } 
        }
    ?>
    </div>
</body>
<script>
let lessonItems = document.querySelectorAll('.lesson-item');
lessonItems.forEach(item => {
    item.addEventListener('click', function() {
        let lessonId = parseInt($(this).attr("id"))
        $.ajax({
            url: "http://localhost:8080/English/modules/course/load_lesson.php",
            method: "post",
            contentType: "application/json",
            data: JSON.stringify({
                id: lessonId
            })
        }).done(function(result) {
            $(".main-learning").html(result);
        })
    });
});
let active = false;
let startTime = null;

window.onfocus = () => {
    active = true;
    startTime = Date.now();
    console.log('Bắt đầu học');
};

window.onblur = () => {
    if (active && startTime) {
        // Tính số phút đã học
        const studySeconds = Math.floor((Date.now() - startTime) / 1000);
        const studyMinutes = Math.ceil(studySeconds / 60);
        if (studyMinutes > 0) {
            // Gửi lên server
            fetch("http://localhost:8080/English/modules/course/study-time.php", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'minutes=' + studyMinutes
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Server:', data);
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                });
        }
    }
    active = false;
    startTime = null;
};

// Bắt đầu theo dõi khi load trang
window.onfocus();
</script>

</html>