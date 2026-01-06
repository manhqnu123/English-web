<link rel="stylesheet" href="./css/style.css?ver=<?php echo rand(); ?>">
<link rel="stylesheet" href="./css/books.css?ver=<?php echo rand(); ?>">
<link rel="stylesheet" href="./css/course.css?ver=<?php echo rand(); ?>">
<link rel="stylesheet" href="./css/exam.css?ver=<?php echo rand(); ?>">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<header class="nav-menu">
    <div class="nav-banner--logo">
        <img src="./logo/Fluentez_Text.f_YBxcCb.jpg" alt="">
    </div>
    <div class="nav-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Tìm kiếm khóa học, bài viết, từ vựng" id="input-search">
        <div class="search-result"></div>
    </div>
    <div class="nav-menu--user">
        <i class="fa-regular fa-comment-dots"></i>
        <i class="fa-regular fa-bell"></i>
    </div>
    <?php 
        if (isset($_SESSION['user'])) {
            echo '<div class="nav-user--info">
                    <img src="modules/uploads/'.$_SESSION['user']['avt'].'" alt="" id="userAvatar">
                    <div class="dropdown-content" id="menuDropdown">
                        <a href="http://localhost:8080/English/?module=auth&action=info&id='.$_SESSION['user']['user_id'].'">Trang cá nhân</a>
                        <a href="http://localhost:8080/English/?module=auth&action=info&id='.$_SESSION['user']['user_id'].'"">Quá trình học tập</a>
                        <a href="http://localhost:8080/English/?module=auth&action=edit&id='.$_SESSION['user']['user_id'].'"">Chỉnh sửa thông tin</a>
                        <a href="http://localhost:8080/English/modules/auth/logout.php">Đăng xuất</a>
                    </div>
                </div>';
        } else {
            echo '<a class="btn-login" href="http://localhost:8080/English/?module=auth&action=login">
                    <button class="btn-login">Đăng nhập</button>
                </a>';
        }
    ?>
</header>

<script type="text/javascript">
const btn = document.querySelector("#userAvatar");
const menu = document.getElementById("menuDropdown");

btn.addEventListener("click", () => {
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
});

// Đóng menu khi click ra ngoài
document.addEventListener("click", function(e) {
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = "none";
    }
});
let debounceTimer;
$('#input-search').on('input', function() {
    let keyword = $(this).val();
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function() {
        performSearch(keyword);
    }, 100);
});

function performSearch(keyword) {
    $('.search-result').html('');
    $.ajax({
        url: "http://localhost:8080/English/modules/course/search.php",
        method: "post",
        contentType: "application/json",
        data: JSON.stringify({
            keyword: keyword
        })
    }).done(function(result) {
        $(".search-result").html(result);
    })
}
$(document).on('click', function(e) {
    // Kiểm tra xem click có nằm trong .nav-search không
    if (!$(e.target).closest('.nav-search').length) {
        $('.search-result').hide();
    }
});
$('#input-search').on('focus', function() {
    if ($(this).val().trim() && $('.search-result').children().length > 0) {
        $('.search-result').show();
    }
});
</script>