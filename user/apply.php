<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../inc/db.php';

if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'];
    $jobseeker_id = $_SESSION['jobseeker_id'];

    echo $job_id . ' ' . $jobseeker_id;
    if (!$job_id) {
        die("No job ID provided.");
    }

    // Check if the job is already applied
    $check_sql = "SELECT application_id FROM applications WHERE job_id = ? AND jobseeker_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    if (!$check_stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $check_stmt->bind_param("ss", $job_id, $jobseeker_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('You have already applied for this job.'); window.history.back();</script>";
        // header("Location: job_details.php?job_id=$job_id");
        exit();
    } else {

        $application_id = 'AS' . rand(1000, 9999);

        // Insert application
        $apply_sql = "INSERT INTO applications (application_id, job_id, jobseeker_id) VALUES (?,?,?)";
        $apply_stmt = $conn->prepare($apply_sql);
        $apply_stmt->bind_param("sss", $application_id, $job_id, $jobseeker_id);
        if ($apply_stmt->execute()) {
            echo "<script>alert('Application submitted successfully!.'); window.history.back();</script>";
            exit();
        } else {
            echo "<p style='color:red;'>Error submitting application: " . $conn->error . "</p>";
        }
    }
}
?>