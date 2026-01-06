<?php
    require_once 'C://xamppserver//htdocs//English//database//connect.php';
    
    $user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $sql = "SELECT status FROM userexamresult WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    
    $passCount = 0;
    $failedCount = 0;
    
    if($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if($row['status'] == 'pass') {
                $passCount++;
            } else if ($row['status'] == 'failed') {
                $failedCount++;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        position: relative;
        font-family: Arial, sans-serif;
    }

    .chart-container {
        width: 400px;
        height: 400px;
        position: absolute;
        text-align: center;
        top: 0;
        left: 10%;
    }

    .user-info {
        display: flex;
        position: absolute;
        left: calc(100% - 50%);
        gap: 30px;
    }

    .user-avt {
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }

    .user-avt img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    strong {
        font-size: 2em;
    }
    </style>
</head>

<body>
    <div class="chart-container">
        <h1>Biểu đồ kết quả thi</h1>
        <canvas id="myPieChart"></canvas>
    </div>
    <div class="user-info">
        <?php
        $sql_user = "select * from user where user_id = $user_id";
        $rs_user = mysqli_query($conn,$sql_user);
        $data = mysqli_fetch_assoc($rs_user);
        echo '<div class="user-avt"><img src="modules/uploads/'.$data['avt'].'"></div>
              <div class="user-content">
                <strong>'.$data['username'].'</strong>
                <p>Email: '.$data['email'].'</p>
                <p>Age: '.$data['age'].'</p>
                <p>Join At: '.$data['created_at'].'</p>
              </div>
        '
     ?>
    </div>
</body>
<script>
let passCount = <?php echo $passCount; ?>;
let failedCount = <?php echo $failedCount; ?>;
let ctx = document.getElementById('myPieChart').getContext('2d');

let myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pass', 'Failed'],
        datasets: [{
            label: 'Số lượng',
            data: [passCount, failedCount],
            backgroundColor: ['#36a2eb', '#ff6384'],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Kết quả thi'
            }
        }
    }
});
</script>

</html>