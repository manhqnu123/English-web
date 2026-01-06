<?php
    require_once 'C://xamppserver//htdocs//English//database//connect.php';
    
    $sql = "SELECT u.user_id, u.username, SUM(ul.total_minutes) as total_minutes 
            FROM user u 
            LEFT JOIN user_study_total ul ON u.user_id = ul.user_id 
            WHERE u.role = 'học viên'
            GROUP BY u.user_id, u.fullname
            ORDER BY total_minutes DESC";
    
    $result = mysqli_query($conn, $sql);
    
    $labels = [];
    $data = [];
    
    if($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $labels[] = $row['username'];
            $data[] = $row['total_minutes'];
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê học tập</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }

    .chart-container {
        width: 90%;
        max-width: 1000px;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #333;
        margin-bottom: 30px;
    }
    </style>
</head>

<body>
    <h1>Biểu đồ thống kê kết quả học tập theo thời gian học</h1>
    <div class="chart-container">
        <canvas id="myBarChart"></canvas>
    </div>
</body>
<script>
const labels = <?php echo json_encode($labels); ?>;
const data = <?php echo json_encode($data); ?>;

const ctx = document.getElementById('myBarChart').getContext('2d');
const myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Thời gian học (phút)',
            data: data,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },

            tooltip: {
                callbacks: {
                    label: function(context) {
                        let minutes = context.parsed.y;
                        let hours = (minutes / 60).toFixed(1);
                        return 'Thời gian: ' + minutes + ' phút (' + hours + ' giờ)';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + ' phút';
                    }
                },
                title: {
                    display: true,
                    text: 'Thời gian (phút)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Học viên'
                }
            }
        }
    }
});
</script>

</html>