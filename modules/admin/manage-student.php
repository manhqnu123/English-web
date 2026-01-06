<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage</title>
    <link rel="stylesheet" href="./css/admin.css?ver=<?php echo rand(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="manage-container">
        <div class="user-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Tìm kiếm học viên" id="user-search">
        </div>
        <div class="manage-list">
            <?php
require_once 'C://xamppserver//htdocs//English//database//connect.php';
$user_sql = "select user_id, username, role, avt,created_at from user where role = 'học viên'";
$result = mysqli_query($conn, $user_sql);
while ($row = mysqli_fetch_array($result)) {
    echo '<div class="manage-item" >
            <div class="manage-group-user">
                <img src="modules/uploads/'.$row['avt'].'">
                <div class="group-user-info">
                    <strong>'.$row['username'].'</strong>
                    <p>Join At: '.$row['created_at'].'</p>
                </div>
            </div>
            <div class="manage-group-btn">
                <a href="http://localhost:8080/English/?module=admin&action=view-student&id='.$row['user_id'].'"><button class="btn-view">Xem</button></a>
                <button class="btn-delete" data-id="'.$row['user_id'].'">Xóa</button>
            </div>
        </div>';
}
?>
        </div>
    </div>
</body>
<script>
let debounceTimer;
$('#user-search').on('input', function() {
    let keyword = $(this).val();
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function() {
        performSearch(keyword);
    }, 100);
});

function performSearch(keyword) {
    $.ajax({
        url: "http://localhost:8080/English/modules/admin/search.php",
        method: "post",
        contentType: "application/json",
        data: JSON.stringify({
            keyword: keyword
        })
    }).done(function(result) {
        $(".manage-item").html(result);
    })
}
$('.btn-delete').on('click', function() {
    let studentID = $(this).data('id');
    if (confirm('Có muốn xóa học viên?')) {
        $.ajax({
            url: "http://localhost:8080/English/modules/admin/delete.php",
            method: "post",
            contentType: "application/json",
            data: JSON.stringify({
                studentID: studentID
            })
        }).done(function(result) {
            alert(result);
            location.reload();
        }).fail(function() {
            alert("Có lỗi xảy ra. Vui lòng thử lại.")
        })
    }
})
</script>

</html>