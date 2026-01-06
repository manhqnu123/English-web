  <div class="container">
      <?php
        require_once './database/connect.php';
            require_once './templates/sidebar.php';
            require_once './templates/header.php';
        ?>
  </div>
  <div class="main-exam">
      <?php
        #display exam list
        $sql = "select exam.exam_id, exam.exam_title, exam.duration, 
        count(userexamresult.exam_id) as total from exam
        left join userexamresult on exam.exam_id = userexamresult.exam_id
        group by exam.exam_id
        ";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
            echo '<div class="exam-item">
                    <h3 class="exam-title">'.$row['exam_title'].'</h3>
                    <div class="exam-content">
                        <p class="exam-duration">'.$row['duration'].'</p>
                        <p class="exam-users">'.$row['total'].'
                        <i class="fa-solid fa-user"></i>
                        </p>
                    </div>
                    <a href="http://localhost:8080/English/?module=exam&action=exam-start&id='.$row['exam_id'].'" class="exam-start--button">Bắt đầu</a>
            </div>';
        }
       ?>
  </div>