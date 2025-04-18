<?php
require_once "../php/dp.php";

// Fetch all events from the DB
$events = [];

try {
    $stmt = $pdo->query("SELECT * FROM calendar_events");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching events: " . $e->getMessage();
}
?>

<?php
// Get current month and year
$currentMonth = date('F'); // e.g., "April"
$currentYear = date('Y');  // e.g., "2025"
$currentDay = date('j'); // e.g., "15"
?>

<?php
$month = date('n'); // 1 = January, 4 = April
$year = date('Y');

// Get total days in current month
$totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Get the weekday of the first day of the month (0 = Sunday, 6 = Saturday)
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Calendar</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- JS (Bootstrap requires these) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light pink-navbar">
        <a class="navbar-brand" href="../html/index.html">Note Taking Website</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav" title="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../html/note_take.html">Note Taking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../html/calendar.html">Calendar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../php/logout.php"
                        onclick="return confirm('Are you sure you want to log out?')">
                        Log out
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">

        <h2 class="text-center mb-4"><?php echo $currentMonth . " " . $currentDay . " " . $currentYear; ?></h2>


        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <table class="table table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $day = 1;
                    $currentCell = 0;

                    // We create rows while days are still available
                    for ($row = 0; $day <= $totalDays; $row++) {
                        echo "<tr>";
                        for ($col = 0; $col < 7; $col++) {
                            if ($row == 0 && $col < $firstDayOfMonth) {
                                // Print empty cells before the first day
                                echo "<td></td>";
                            } elseif ($day <= $totalDays) {
                                echo "<td>";
                                echo $day;

                                // Display all events that match this day
                                foreach ($events as $event) {
                                    if ($event['day'] == $day) {
                                        echo "<span class='event-span'>";
                                        echo htmlspecialchars($event['title']);
                                        echo "<form method='post' action='../php/edit_or_delete.php' style='display:inline-block; margin-left:5px;'>";
                                        echo "<input type='hidden' name='id' value='" . $event['id'] . "'>";
                                        echo "<button type='submit' name='edit' class='btn btn-sm btn-light'>E</button>";
                                        echo "<button type='submit' name='D' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this event?');\">D</button>";
                                        echo "</form>";
                                        echo "</span>";
                                    }
                                }


                                echo "</td>";

                                $day++;
                            } else {
                                echo "<td></td>"; // empty cells after last day
                            }
                            $currentCell++;
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>



            <!-- Add Event Form -->
            <div class="mt-5">
                <h3 class="text-center mb-3">Add Event</h3>
                <form action="../php/save_event.php" method="post" class="card p-4 shadow pink-card">
                    <div class="form-group">
                        <label for="title">Event Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="day">Day of Month</label>
                        <input type="number" name="day" id="day" class="form-control" min="1" max="30" required>
                    </div>

                    <button type="submit" name="create_event" class="btn btn-pink mt-3">Add Event</button>
                </form>
            </div>

        </div>

</body>

</html>