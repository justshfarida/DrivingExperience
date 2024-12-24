<?php
// Start session and include the database connection
session_start();
include("connectDB.inc.php");

// Ensure the session code array is initialized
$_SESSION['code'] = [];

$db = Database::getInstance();
$pdo = $db->getConnection();

// Fetch weather distribution data from the database
$weatherQuery = "
    SELECT w.weather_condition, COUNT(*) as count
    FROM Driving_Experience de
    JOIN Weather w ON de.weather_id = w.weather_id
    GROUP BY w.weather_condition
";
$weatherData = $pdo->query($weatherQuery)->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for the chart
$weatherLabels = [];
$weatherCounts = [];
foreach ($weatherData as $row) {
    $weatherLabels[] = $row['weather_condition'];
    $weatherCounts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driving Experience List</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/shared.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <h1>Driving Experience List</h1>
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

                try {
                    $stmt = $pdo->query($query);
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch()) {
                            // Generate a unique session code for each experience
                            $code = bin2hex(random_bytes(10));
                            $_SESSION['code'][$code] = $row['experience_id'];

                            // Output table row with Edit and Delete links
                            echo "<tr>
                                <td>{$row['start_time']}</td>
                                <td>{$row['end_time']}</td>
                                <td>{$row['distance']} km</td>
                                <td>{$row['weather_name']}</td>
                                <td>{$row['road_name']}</td>
                                <td>{$row['traffic_name']}</td>
                                <td>{$row['maneuvers']}</td>
                                <td>
                                    <a href=\"webForm.php?mode=edit&code=$code\">Edit</a>
                                    <a href=\"deleteHandler.php?code=$code\" onclick=\"return confirm('Are you sure you want to delete this entry?');\">Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No driving experiences found.</td></tr>";
                    }
                } catch (PDOException $e) { 
                    echo "Error: " . $e->getMessage();
                    exit();
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pie Chart Section -->
    <div class="chart-container" style="width: 50%; margin: 20px auto;">
        <canvas id="weatherChart"></canvas>
    </div>

    <div class="text-center buttons">
        <?php
        $code = bin2hex(random_bytes(10));
        $_SESSION['code'][$code] = 0;
        ?>
        <a href="webForm.php?mode=new&code=<?php echo $code; ?>" class="btn">Add New Driving Experience</a>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#experienceTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });
    });

    // PHP Data for Chart.js
    const weatherLabels = <?php echo json_encode($weatherLabels); ?>;
    const weatherCounts = <?php echo json_encode($weatherCounts); ?>;

    // Create Pie Chart for Weather Distribution
    const ctx = document.getElementById('weatherChart').getContext('2d');
    const weatherChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: weatherLabels,
            datasets: [{
                label: 'Weather Conditions',
                data: weatherCounts,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
</body>
</html>
