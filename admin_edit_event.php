<?php
// done by zixu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_POST['event_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'fyp_app');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event_id = $_POST['event_id'];

$event;
$result = $conn->query("SELECT * FROM event WHERE event_id = '$event_id'");
while ($row = $result->fetch_assoc()) {
    $event = $row;
}

// if ($conn->query($sql) === TRUE) {
//     header("Location: dashboard.php");
//     exit();
// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }

?>
<main>
  <section class="display-events">
    <div class="container">
      <h3>Edit Event</h3>
        <form action="admin_events.php" method="POST">
          <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" value="<?php echo $event['description'] ?>" required>
          </div>
          <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $event['start_date'] ?>" required>
          </div>
          <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo $event['end_date'] ?>" required>
          </div>
          <div class="form-group">
            <label for="image_url">Image URL:</label>
            <input type="text" id="image_url" name="image_url" value="<?php echo $event['image_url'] ?>" required>
          </div>
          <div class="form-group">
            <label for="points">Points:</label>
            <input type="number" id="points" name="points" value="<?php echo $event['points'] ?>" required>
          </div>
          <div class="form-group">
            <button type="submit" class="btn">Save Event</button>
          </div>
        </form>
    </div>
  </section>
</main>
<?php
$conn->close();
?>
