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
            require_once './templates/sidebar.php';
            require_once './templates/header.php';
        ?>
    </div>
    <div class="main-home">
        <div class="banner-home">
            <img src="logo/banner.jpg" alt="">
        </div>
        <h3>Học tiếng anh dễ dàng cùng fluentez</h3>
    </div>
    <div class="group-course">
        <div class="group-course--item">
            <a href="http://localhost:8080/English/?module=course&action=view-course&name=grammar"><img
                    src="logo/grammar.jpg" alt=""></a>
        </div>
        <div class="group-course--item">
            <a
                href="http://localhost:8080/English/?module=course&action=view-course&name=listening"><img
                    src="logo/listening.jpg" alt=""></a>
        </div>
        <div class="group-course--item">
            <a href="http://localhost:8080/English/?module=course&action=view-course&name=reading"><img
                    src="logo/reading.jpg" alt=""></a>
        </div>
        <div class="group-course--item">
            <a href="http://localhost:8080/English/?module=course&action=view-course&name=speaking"><img
                    src="logo/speaking.jpg" alt=""></a>
        </div>
        <div class="group-course--item">
            <a href="http://localhost:8080/English/?module=course&action=view-course&name=writing"><img
                    src="logo/writing.jpg" alt=""></a>
        </div>
    </div>
    <div class="footer">
        <div class="footer-item">
            <div class="footer-item--logo">
                <img src="logo/Fluentez2.BWlS2kKf.png" alt="">
                <span class="font-bold">Master English With Fluentez</span>
            </div>
            <p class="font-bold">Phone: 03xxxxxxxx</p>
            <p class="font-bold">Email: abcdef@gmail.com</p>
        </div>
        <div class="footer-item">
            <p class="font-bold">Company</p>
            <p>About</p>
            <p>Terms</p>
            <p>Policy</p>
        </div>
        <div class="footer-item">
            <p class="font-bold">Course</p>
            <p>Grammar</p>
            <p>Listening</p>
            <p>Reading</p>
            <p>Writing</p>
        </div>
        <div class="footer-item">
            <p class="font-bold">Short Link</p>
            <p>Vocabulary</p>
            <p>Books</p>
        </div>
    </div>
</body>

</html>