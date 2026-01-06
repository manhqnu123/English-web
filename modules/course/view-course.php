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
    <div class="main-content--course">
        <div class="course-grid">
            <?php 
                $is_logged_in = isset($_SESSION['user']);
                $user_id = $_SESSION['user']['user_id'];
                $id_learning ="";
                if (isset($_GET['name'])) {
                    $name = $_GET['name'];
                     $sql = "select * from course where category = '$name'";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $check_course_access = checkCourseAccess($user_id, $row['course_id'],$conn);
                        echo '<div class="course-grid--item" id="'.$row['course_id'].'">
                                <div class="course-img">
                                    <img src="https://i.pinimg.com/1200x/c5/e5/48/c5e548cd39d6014c8e2f5f5c0ea1bad0.jpg">
                                </div>
                                <p class="course-title">'.$row['course_name'].'</p>
                                <p class="course-price">Giá: '.$row['price'].' VNĐ</p>
                                
                            ';
                                if($check_course_access == true) {
                                    echo '<button class="access-learning">Học</button>';
                                } else{
                                    echo '<a href="http://localhost:8080/English/modules/payment/init-pay.php?id='.$row['course_id'].'">
                                            Mua khóa học
                                    </a>';
                                }
                        echo '</div>';
                    }
                    // var_dump($_SESSION['user']['user_id']);
                }
            ?>
        </div>
    </div>
</body>
<script>
const $is_logged_in = <?php echo json_encode($is_logged_in); ?>;
let accessButtons = document.querySelectorAll('.access-learning');

accessButtons.forEach(button => {
    button.addEventListener('click', () => {
        const course_id = Number(button.closest('.course-grid--item').id);
        if ($is_logged_in) {
            window.location.href =
                `http://localhost:8080/English/?module=course&action=learning&id=${course_id}`;
        } else {
            alert('Vui lòng đăng nhập để học khóa học này!');
            window.location.href =
                "http://localhost:8080/English/?module=auth&action=login";
        }
    });
});
</script>

</html>