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
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $image_url = $_POST['image_url'];
    $points = $_POST['points'];

    $stmt = $conn->prepare("UPDATE `event` SET `description`='$description',`start_date`='$start_date',`end_date`='$end_date',`image_url`='$image_url',`points`='$points' WHERE `event_id`='$event_id'");
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}
?>