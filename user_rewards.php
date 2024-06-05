<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';

// Database connection
$conn = new mysqli('localhost', 'root', '', 'fyp_app');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user rewards
$user_id = $_SESSION['user_id'];
$sql = "SELECT coupon.name, user_reward.unique_id, user_reward.redeemed_at FROM user_reward JOIN coupon ON user_reward.coupon_id = coupon.coupon_id WHERE user_reward.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rewards = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rewards[] = $row;
    }
}
$stmt->close();
?>

<main>
    <section class="user-rewards">
        <div class="container">
            <h2>Your Rewards</h2>
            <div class="rewards-list">
                <?php if (empty($rewards)): ?>
                    <p>You have not redeemed any rewards yet.</p>
                <?php else: ?>
                    <?php foreach ($rewards as $reward): ?>
                        <div class="reward-item">
                            <h3><?php echo htmlspecialchars($reward['name']); ?></h3>
                            <p>Unique ID: <?php echo htmlspecialchars($reward['unique_id']); ?></p>
                            <p>Redeemed At: <?php echo htmlspecialchars($reward['redeemed_at']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>

<?php
$conn->close();
?>
