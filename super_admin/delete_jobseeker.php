<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';

// Get jobseeker_id from URL
$jobseeker_id = isset($_GET['jobseeker_id']) ? $_GET['jobseeker_id'] : 0;

if ($jobseeker_id > 0) {
    $stmt = $conn->prepare("DELETE FROM jobseekers WHERE jobseeker_id = ?");
    $stmt->bind_param("s", $jobseeker_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to jobseekers page
header("Location: jobseekers.php");
exit();
