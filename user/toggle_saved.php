<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['jobseeker_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to save a job.']);
    exit();
}

include '../inc/db.php';

$jobseeker_id = $_SESSION['jobseeker_id'];
$job_id = $_POST['job_id'] ?? '';

if (empty($job_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Job ID is required.']);
    exit();
}

// Check if already saved
$check_sql = "SELECT saved_job_id FROM saved_jobs WHERE jobseeker_id = ? AND job_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ss", $jobseeker_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Already saved, so remove it
    $row = $result->fetch_assoc();
    $saved_job_id = $row['saved_job_id'];

    $delete_sql = "DELETE FROM saved_jobs WHERE saved_job_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("s", $saved_job_id);

    if ($delete_stmt->execute()) {
        echo json_encode(['status' => 'removed', 'message' => 'Job removed from saved list']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove saved job']);
    }
} else {
    // Not saved yet, so insert
    $saved_job_id = 'SV' . rand(1000, 9999);
    $insert_sql = "INSERT INTO saved_jobs (saved_job_id, jobseeker_id, job_id, saved_at) VALUES (?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sss", $saved_job_id, $jobseeker_id, $job_id);

    if ($insert_stmt->execute()) {
        echo json_encode(['status' => 'saved', 'message' => 'Job saved successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $insert_stmt->error]);
    }
}
