<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';

$job_id = $_GET['job_id'] ?? '';

if (!$job_id) {
    // Invalid request
    header("Location: manage_jobs.php");
    exit();
}

// Make sure this job belongs to the current admin's company
$company_id = $_SESSION['company_id'];
$admin_id = $_SESSION['company_admin_id'];

$stmt = $conn->prepare("DELETE FROM jobs WHERE job_id = ? AND company_id = ? AND posted_by = ?");
$stmt->bind_param("sss", $job_id, $company_id, $admin_id);
$stmt->execute();

header("Location: manage_jobs.php");
exit();
?>
