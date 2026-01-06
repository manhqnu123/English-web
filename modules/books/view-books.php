<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluentez-Books</title>
</head>
<div class="container">
    <?php
        require_once './database/connect.php';
        require_once './templates/sidebar.php';
        require_once './templates/header.php'; 
    ?>
</div>
<div class="main-content--books">
    <div class="books-grid">
        <?php
        
            $sql = "select * from books";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
                echo '<div class="books-grid--item">
                         <div class="books-img">
                            <img src="'.$row['books_img'].'">
                         </div>
                         <p class="books-title">'.$row['title'].'</p>
                         <a href="'.$row['file_url'].'">Read</a>
                    </div>';
            }
        ?>
    </div>
</div>