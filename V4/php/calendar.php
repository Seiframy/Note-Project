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

        <h2 class="text-center mb-4">April 2025</h2>

        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <div class="day-name">Sun</div>
            <div class="day-name">Mon</div>
            <div class="day-name">Tue</div>
            <div class="day-name">Wed</div>
            <div class="day-name">Thu</div>
            <div class="day-name">Fri</div>
            <div class="day-name">Sat</div>

            <!-- Blank days before the 1st -->
            <div class="day-cell empty"></div>
            <div class="day-cell empty"></div>

            <!-- Actual days 1 - 30 -->
            <?php for ($i = 1; $i <= 30; $i++): ?>
                <div class="day-cell">
                    <?php echo $i; ?>
                    <?php foreach ($events as $event): ?>
                        <?php if ($event['day'] == $i): ?>
                            <div class="event-title"><?php echo htmlspecialchars($event['title']); ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>


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