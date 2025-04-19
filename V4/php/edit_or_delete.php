<?php

require_once __DIR__ . '/session_check.php';  // guard AND $user_id
require_once __DIR__ . '/dp.php';             // DB connection

// Now you’re safe to use $pdo and $user_id

// Handle DELETE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['D'])) {
    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM calendar_events WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        echo "<script>alert('Event deleted.'); window.location.href = '/php/calendar.php';</script>";
    } catch (PDOException $e) {
        echo "Error deleting: " . $e->getMessage();
    }
}

// Handle EDIT
$event = null; // initialize variable
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM calendar_events WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching event: " . $e->getMessage();
        exit;
    }
}
?>

<?php if ($event): ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Event</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>

    <body>

        <h2 class="text-center mt-5">Edit Event</h2>

        <form method="post" action="update_event.php" class="card p-4 pink-card shadow">
            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" name="title" id="title" class="form-control" required value="<?php echo htmlspecialchars($event['title']); ?>">
            </div>

            <div class="form-group mt-3">
                <label for="day">Day of Month</label>
                <input type="number" name="day" id="day" class="form-control" min="1" max="30" required value="<?php echo $event['day']; ?>">
            </div>

            <button type="submit" name="update_event" class="btn btn-pink mt-3">Update</button>
        </form>
    <?php else: ?>
        <p class="text-center text-danger">No event data found. Please go back to the calendar.</p>
    </body>

<?php endif; ?>