<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="./css/style.css?ver=<?php echo rand(); ?>">
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="./logo/Fluentez2.BWlS2kKf.png" alt="Logo">
    </div>

    <div class="sidebar-section">
        <h3 class="section-title">TOP FEATURES</h3>
        <ul class="main-menu">
            <li><a href="http://localhost:8080/English/"><i class="fas fa-chart-line"></i>
                    Dashboard</a></li>

            <li class="has-submenu open">
                <a href="#" class="menu-toggle"><i class="fas fa-book-open"></i> Courses <i
                        class="fas fa-chevron-up submenu-icon"></i></a>
                <ul class="submenu">
                    <li><a
                            href="http://localhost:8080/English/?module=course&action=view-course&name=grammar">Grammar</a>
                    </li>
                    <li><a
                            href="http://localhost:8080/English/?module=course&action=view-course&name=listening">Listening</a>
                    </li>
                    <li><a
                            href="http://localhost:8080/English/?module=course&action=view-course&name=reading">Reading</a>
                    </li>
                    <li>
                        <a
                            href="http://localhost:8080/English/?module=course&action=view-course&name=writting">Writing
                            <span class="badge badge-hot">🔥</span></a>
                    </li>
                    <li><a
                            href="http://localhost:8080/English/?module=course&action=view-course&name=speaking">Speaking</a>
                    </li>
                    <li>
                        <a href="#">IELTS <span class="badge badge-score">7.5+</span></a>
                    </li>
                </ul>
            </li>
            <li><a href="http://localhost:8080/English/?module=books&action=view-books"><i
                        class="fas fa-book"></i> Books</a></li>
        </ul>
    </div>

    <div class="sidebar-section">
        <h3 class="section-title">ADVANCED</h3>
        <ul class="main-menu">
            <li class="has-submenu open">
                <a href="#" class="menu-toggle">
                    <i class="fas fa-trophy"></i> Certificates
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a
                            href="http://localhost:8080/English/?module=exam&action=view-exam">IELTS</a>
                    </li>
                    <li><a href="#">TOEIC</a></li>
                </ul>
            </li>

        </ul>
    </div>

    <div class="sidebar-section">
        <h3 class="section-title">ARTICLES</h3>
        <ul class="main-menu">
            <li><a href="#"><i class="fas fa-rss"></i> Blog</a></li>
            <li><a href="#"><i class="fas fa-address-book"></i> Contact</a></li>
            <li>
                <a href="#"><i class="fas fa-donate"></i> Donate <span
                        class="badge badge-heart">❤</span></a>
            </li>
        </ul>
    </div>

    <div class="sidebar-toggle">
        <i class="fas fa-chevron-left"></i>
    </div>
</aside>
<script src="./js/script.js"></script>