<?php
// Start session and include the database connection
session_start();
include("connectDB.inc.php");

// Ensure the session code array is initialized
$_SESSION['code'] = [];

$db = Database::getInstance();
$pdo = $db->getConnection();

// Fetch data for Weather Pie Chart
$weatherQuery = "
    SELECT w.weather_condition AS label, COUNT(*) AS value
    FROM Driving_Experience de
    JOIN Weather w ON de.weather_id = w.weather_id
    GROUP BY w.weather_condition;
";
$weatherData = $pdo->query($weatherQuery)->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for Road Pie Chart
$roadQuery = "
    SELECT r.road_condition AS label, COUNT(*) AS value
    FROM Driving_Experience de
    JOIN Road r ON de.road_id = r.road_id
    GROUP BY r.road_condition;
";
$roadData = $pdo->query($roadQuery)->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for Bar Chart (Driving Experiences by Hour of Day)
$hourQuery = "
    SELECT HOUR(start_time) AS hour_of_day, COUNT(*) AS num_experiences
    FROM Driving_Experience
    GROUP BY hour_of_day
    ORDER BY hour_of_day;
";
$hourData = $pdo->query($hourQuery)->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for Line Chart (Distance Covered by Traffic Condition)
$distanceQuery = "
    SELECT t.traffic_condition AS label, AVG(de.distance) AS avg_distance
    FROM Driving_Experience de
    JOIN Traffic t ON de.traffic_id = t.traffic_id
    GROUP BY t.traffic_condition;
";
$distanceData = $pdo->query($distanceQuery)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driving Experience Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <h1>Driving Experience Dashboard</h1>
    <div class="dashboard-content">
        <!-- Table Section -->
        <div class="table-wrapper">
            <table id="experienceTable">
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Distance</th>
                        <th>Weather</th>
                        <th>Road</th>
                        <th>Traffic</th>
                        <th>Maneuvers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "
                        SELECT 
                            de.experience_id,
                            de.start_time,
                            de.end_time,
                            de.distance,
                            w.weather_condition AS weather_name,
                            r.road_condition AS road_name,
                            t.traffic_condition AS traffic_name,
                            GROUP_CONCAT(m.maneuver_type ORDER BY m.maneuver_id) AS maneuvers
                        FROM 
                            Driving_Experience de
                        JOIN 
                            Weather w ON de.weather_id = w.weather_id
                        JOIN 
                            Road r ON de.road_id = r.road_id
                        JOIN 
                            Traffic t ON de.traffic_id = t.traffic_id
                        LEFT JOIN 
                            Driving_Maneuvers dm ON de.experience_id = dm.experience_id
                        LEFT JOIN 
                            Maneuvers m ON dm.maneuver_id = m.maneuver_id
                        GROUP BY 
                            de.experience_id
                        ORDER BY 
                            de.experience_id;
                    ";
                    $stmt = $pdo->query($query);
                    while ($row = $stmt->fetch()) {
                        $code = bin2hex(random_bytes(10));
                        $_SESSION['code'][$code] = $row['experience_id'];
                        echo "<tr>
                                <td>{$row['start_time']}</td>
                                <td>{$row['end_time']}</td>
                                <td>{$row['distance']} km</td>
                                <td>{$row['weather_name']}</td>
                                <td>{$row['road_name']}</td>
                                <td>{$row['traffic_name']}</td>
                                <td>{$row['maneuvers']}</td>
                                <td>
                                    <a href='webForm.php?mode=edit&code=$code'>Edit</a>
                                    <a href='deleteHandler.php?code=$code' onclick='return confirm(\"Are you sure you want to delete this entry?\");'>Delete</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center buttons">
        <?php
            $code = bin2hex(random_bytes(10));
            $_SESSION['code'][$code] = 0;
        ?>
        <a href="webForm.php?mode=new&code=<?php echo $code; ?>" class="btn">Add New Driving Experience</a>
    </div>

    <!-- Statistics Section -->
    <h2 class="statistics-header">Driving Statistics</h2>
    <div class="charts-wrapper">
        <!-- Weather Pie Chart -->
        <div class="chart-container">
            <canvas id="weatherChart"></canvas>
            <p>This chart shows the distribution of weather conditions during driving experiences.</p>
        </div>
        <!-- Road Pie Chart -->
        <div class="chart-container">
            <canvas id="roadChart"></canvas>
            <p>This chart illustrates the types of road conditions encountered in driving experiences.</p>
        </div>
        <!-- Bar Chart -->
        <div class="chart-container">
            <canvas id="barChart"></canvas>
            <p>This bar chart displays the frequency of driving experiences by the hour of the day.</p>
        </div>
        <!-- Line Chart -->
        <div class="chart-container">
            <canvas id="lineChart"></canvas>
            <p>This line chart highlights the average distance covered under different traffic conditions.</p>
        </div>
    </div>
</div>
<div class="container foot">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <div class="col-md-4 d-flex align-items-center">
      <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
        <img src="img/logo.webp" alt="Logo" width="30" height="30" class="d-inline-block align-text-top circular-logo">
      </a>
      <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Farida's Driving Experience</span>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-twitter"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-instagram"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-facebook"></i></a></li>
    </ul>
  </footer>
</div>

<script src="js/charts.js"></script>
</body>
<script>
    $(document).ready(function () {
        $('#experienceTable').DataTable({
            scrollX: true,
            responsive: true
        });
    });
    // Fetch data dynamically from PHP variables (already prepared)
const weatherData = {
    labels: <?php echo json_encode(array_column($weatherData, 'label')); ?>,
    values: <?php echo json_encode(array_column($weatherData, 'value')); ?>
};
const roadData = {
    labels: <?php echo json_encode(array_column($roadData, 'label')); ?>,
    values: <?php echo json_encode(array_column($roadData, 'value')); ?>
};
const hourlyData = {
    labels: <?php echo json_encode(array_column($hourData, 'hour_of_day')); ?>,
    values: <?php echo json_encode(array_column($hourData, 'num_experiences')); ?>
};
const distanceData = {
    labels: <?php echo json_encode(array_column($distanceData, 'label')); ?>,
    values: <?php echo json_encode(array_column($distanceData, 'avg_distance')); ?>
};

// Render Weather Pie Chart
new Chart(document.getElementById('weatherChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: weatherData.labels,
        datasets: [{
            data: weatherData.values,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#AA8F2A'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Render Road Pie Chart
new Chart(document.getElementById('roadChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: roadData.labels,
        datasets: [{
            data: roadData.values,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#AA8F2A', '#FF9F40'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Render Bar Chart
new Chart(document.getElementById('barChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: hourlyData.labels,
        datasets: [{
            label: 'Number of Driving Experiences',
            data: hourlyData.values,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            x: { title: { display: true, text: 'Hour of the Day (24-Hour Format)' } },
            y: { title: { display: true, text: 'Number of Experiences' }, beginAtZero: true }
        }
    }
});

// Render Line Chart
new Chart(document.getElementById('lineChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: distanceData.labels,
        datasets: [{
            label: 'Average Distance (km)',
            data: distanceData.values,
            borderColor: '#FF6384',
            fill: false,
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            x: { title: { display: true, text: 'Traffic Conditions' } },
            y: { title: { display: true, text: 'Average Distance (km)' }, beginAtZero: true }
        }
    }
});

</script>
</html>
