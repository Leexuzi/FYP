<?php
// done by leezixu
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
$result = $conn->query("SELECT * FROM rewards");
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

// here
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $points_required = $_POST['points_required'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("INSERT INTO rewards (name, points_required, description, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $name, $points_required, $description, $image_url);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}

include 'header.php';
?>

<main>
    <section class="create-rewards">
        <div class="container">
            <h2>Create New Reward</h2>
            <form action="admin_create_events.php" method="POST">
                <div class="form-group">
                    <label for="description">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Points Required:</label>
                    <input type="date" id="points_required" name="points_required" required>
                </div>
                <div class="form-group">
                    <label for="end_date">Description:</label>
                    <input type="date" id="description" name="description" required>
                </div>
                <div class="form-group">
                    <label for="image_url">Image URL:</label>
                    <input type="text" id="image_url" name="image_url" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Create Reward</button>
                </div>
            </form>
        </div>
    </section>
  <section class="display-rewards">
    <div class="container">
      <h3>Rewards</h3>
                <div class="activity-list">
                  <?php foreach ($rewards as $reward): ?>
                    <div class="activity">
                      <img src="<?php echo htmlspecialchars($reward['image_url']); ?>" alt="<?php echo htmlspecialchars($reward['name']); ?>" class="activity-image">
                            <h3><?php echo htmlspecialchars($reward['name']); ?></h3>
                            <p><strong>Points Required:</strong> <?php echo htmlspecialchars($reward['points_required']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($reward['description']); ?></p>
                            <form action="admin_edit_reward.php" method="POST">
                                <input type="hidden" name="reward_id" value="<?php echo htmlspecialchars($reward['reward_id']); ?>">
                                <button type="submit" class="btn">Edit Reward</button>
                            </form>
                            <form action="admin_delete_reward.php" method="POST">
                                <input type="hidden" name="reward_id" value="<?php echo htmlspecialchars($reward['reward']); ?>">
                                <button type="submit" class="btn">Delete Reward</button>
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
