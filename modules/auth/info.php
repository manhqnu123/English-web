<div class="container">
    <?php
        require_once './templates/sidebar.php';
        require_once './templates/header.php';
    ?>
</div>
<div class="main-user--info">
    <?php 
 require_once "C://xamppserver//htdocs//English//database//connect.php";
 $user_id = $_SESSION['user']['user_id'];
 $sql = "select * from user where user_id = $user_id";
 $result = mysqli_query($conn, $sql);
 while($row = mysqli_fetch_array($result)) {
    echo '<div class="user-banner">
        <img src="logo/banner.png" alt="">
        <div class="avt-user">
            <img src="modules/uploads/'.$row['avt'].'"
                alt="">
        </div>
        <div class="user-details">
            <div class="info-user">
                <p class="fullname">'.$row['fullname'].'</p>
                <p>Join At '.$row['created_at'].'</p>
            </div>
            <div class="level-user">
                <p>Cấp bậc: level 1</p>
            </div>
        </div>
    </div>';
 }
 $sql_study = "select total_minutes from user_study_total where user_id = $user_id";
 $result_study = mysqli_query($conn, $sql_study);
 $study_time = mysqli_fetch_array($result_study);
 $sql_exam = "select count(*) as total_exams from userexamresult where user_id = $user_id";
 $result_exam = mysqli_query($conn, $sql_exam);
 $result_exam_final = mysqli_fetch_array($result_exam);
 echo '<div class="process-study">
        <div class="exam">
            <p>Số đề đã làm</p>
            <p>'.$result_exam_final['total_exams'].'</p>
            <p>Đề thi</p>
        </div>
        <div class="exam">
            <p>Thời gian đã học</p>
            <p>'.$study_time['total_minutes'].'</p>
            <p>phút</p>
        </div>
    </div>'
?>

</div>