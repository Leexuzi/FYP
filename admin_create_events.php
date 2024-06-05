<?php
// done by zixu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// Include database connection
$conn = new mysqli('localhost', 'root', '', 'fyp_app');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$events = [];
$result = $conn->query("SELECT * FROM event");
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $image_url = $_POST['image_url'];
    $points = $_POST['points'];

    $stmt = $conn->prepare("INSERT INTO event (description, start_date, end_date, image_url, points) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param($description, $start_date, $end_date, $image_url, $point);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}

include 'header.php';
?>

<main>
    <section class="create-event">
        <div class="container">
            <h2>Create New Event</h2>
            <form action="admin_events.php" method="POST">
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label for="image_url">Image URL:</label>
                    <input type="text" id="image_url" name="image_url" required>
                </div>
              <div class="form-group">
                    <label for="points">Points:</label>
                    <input type="number" id="points" name="points" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Create Event</button>
                </div>
            </form>
        </div>
    </section>
  <section class="display-events">
    <div class="container">
      <h3>Events</h3>
                <div class="activity-list">
                  <?php foreach ($events as $event): ?>
                    <div class="activity">
                      <img src="<?php echo htmlspecialchars($event['image_url']); ?>" alt="<?php echo htmlspecialchars($event['description']); ?>" class="activity-image">
                            <h3><?php echo htmlspecialchars($event['description']); ?></h3>
                            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($event['start_date']); ?></p>
                            <p><strong>End Date:</strong> <?php echo htmlspecialchars($event['end_date']); ?></p>
                            <p><strong>Points:</strong> <?php echo htmlspecialchars($event['points']); ?></p>
                            <form action="admin_edit_event.php" method="POST">
                                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['event_id']); ?>">
                                <button type="submit" class="btn">Edit Event</button>
                            </form>
                            <form action="admin_delete_event.php" method="POST">
                                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['event_id']); ?>">
                                <button type="submit" class="btn">Delete Event</button>
                            </form>
                        </div>
                  <?php endforeach; ?>
                </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

<?php
$conn->close();
?>
