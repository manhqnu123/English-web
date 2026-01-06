<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
    }

    .main-edit--info {
        max-width: 600px;
        margin: 50px auto;
        background: white;
        padding: 40px;
        border: 3px solid #4a90e2;
        border-radius: 8px;
        position: relative;
        top: -600px;
        left: 5%;
    }

    .form-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #333;
    }

    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .form-group label {
        width: 150px;
        font-weight: bold;
        font-size: 16px;
        color: #333;
    }

    .form-group input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-group input:focus {
        outline: none;
        border-color: #4a90e2;
    }

    .button-group {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 40px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-choose {
        background: white;
        color: #333;
    }

    .btn-choose:hover {
        background: #f0f0f0;
    }

    .btn-submit {
        background: #4caf50;
        color: white;
        border: none;
    }

    .btn-submit:hover {
        background: #45a049;
    }

    .btn-submit:disabled {
        background: #cccccc;
        cursor: not-allowed;
    }

    .message {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 4px;
        text-align: center;
        display: none;
    }

    .message.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .message.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .image-preview {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .image-preview img {
        max-width: 100px;
        max-height: 100px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    #image-file {
        display: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <?php
            require_once './templates/sidebar.php';
            require_once './templates/header.php';
        ?>
    </div>

    <div class="main-edit--info">
        <h2 class="form-title">Chỉnh sửa thông tin</h2>

        <div id="message" class="message"></div>

        <form id="editInfoForm">
            <div class="form-group">
                <label for="fullname">Họ tên :</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>

            <div class="form-group">
                <label for="age">Tuổi :</label>
                <input type="number" id="age" name="age" required min="1" max="120">
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">SĐT:</label>
                <input type="tel" id="phone" name="phone" required pattern="[0-9]{10,11}">
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-choose"
                    onclick="document.getElementById('image-file').click()">
                    Chọn ảnh
                </button>
                <input type="file" id="image-file" name="image" accept="image/*"
                    onchange="previewImage(event)">

                <button type="submit" class="btn btn-submit" id="submitBtn">Lưu</button>
            </div>

            <div class="image-preview" id="imagePreview"
                style="margin-top: 20px; justify-content: center;"></div>
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    var imageBase64 = null;
    // Xem trước ảnh
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imageBase64 = e.target.result;
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            reader.readAsDataURL(file);
        }
    }
    $("#submitBtn").click((e) => {
        e.preventDefault();
        let fullname = $("#fullname").val();
        let age = $("#age").val();
        let pass = $("#password").val();
        let email = $("#email").val();
        let phone = $("#phone").val();
        let user_id = <?php echo $_SESSION['user']['user_id'] ?>;
        // console.log(imageBase64)
        $.ajax({
            url: "http://localhost:8080/English/modules/auth/edit-post.php",
            method: "post",
            contentType: "application/json",
            data: JSON.stringify({
                id: user_id,
                fullname: fullname,
                pass: pass,
                phone: phone,
                age: age,
                email: email,
                image: imageBase64
            })
        }).done(function(result) {
            alert(result);
        })
    })
    </script>
</body>

</html>