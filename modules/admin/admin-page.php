<?php
    if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not permitted');
}
// Kiểm tra admin
if (!isset($_SESSION['user']['role']) || trim($_SESSION['user']['role']) != 'admin') {
    header("Location: http://localhost:8080/English/?module=error&action=403");
    exit;
}
 ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluentez - Dashboard</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #fff;
        min-height: 100vh;
        padding: 20px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 40px;
        background: white;
        border-radius: 15px;
        margin-bottom: 40px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-icon {
        height: 100px;
    }

    .logo-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-icon {
        width: 45px;
        height: 45px;
        background: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e0e0e0;
    }

    .user-icon:hover {
        background: #e0e0e0;
        transform: scale(1.05);
    }

    .user-icon svg {
        width: 24px;
        height: 24px;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 20px;
    }

    .card {
        background: white;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card-blue {
        background: #2562fe;
    }

    .card-cyan {
        background: #00ccd3;
    }

    .card-yellow {
        background: #d6dc2b;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .card-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        background: white;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .card-icon img {
        width: 70px;
        height: 70px;
        object-fit: contain;
    }

    .card-title {
        font-size: 22px;
        font-weight: 600;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .header {
            padding: 15px 20px;
        }

        .logo-text {
            font-size: 22px;
        }

        .cards-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .card {
            padding: 30px 20px;
        }
    }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <div class="logo-icon"><img src="logo/Fluentez_Text.f_YBxcCb.jpg" alt=""></div>
        </div>
        <div class="user-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="cards-grid">
            <div class="card card-cyan"
                onclick="location.href='?module=admin&action=manage-student'">
                <div class="card-icon">
                    <img src="https://i.pinimg.com/736x/90/32/1b/90321b941d0a8596ffde560f7df359c3.jpg"
                        alt="Students">
                </div>
                <div class="card-title">Quản lý học viên</div>
            </div>

            <div class="card card-yellow"
                onclick="location.href='?module=admin&action=result-learning'">
                <div class="card-icon">
                    <img src="https://i.pinimg.com/736x/90/32/1b/90321b941d0a8596ffde560f7df359c3.jpg"
                        alt="Statistics">
                </div>
                <div class="card-title">Thống kê học tập</div>
            </div>
        </div>
    </div>

    <script>
    // Thêm hiệu ứng click cho user icon
    document.querySelector('.user-icon').addEventListener('click', function() {
        alert('Dropdown menu - Bạn có thể thêm menu profile, logout, etc.');
    });

    // Animation khi load trang
    window.addEventListener('load', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 10);
            }, index * 100);
        });
    });
    </script>
</body>

</html>