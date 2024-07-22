<?php
// done by zixu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'fyp_app');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $points_required = $_POST['points_required'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $reward_id = $_POST['reward_id'];


    $sql = "UPDATE rewards SET name='$name', points_required='$points_required', description='$description', image_url='$image_url' WHERE reward_id='$reward_id'";
    $conn->query($sql);
    $conn->close();

    header("Location: admin_create_rewards.php");
    exit();
}
?>