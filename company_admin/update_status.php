<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

include '../inc/db.php';

// Make sure all parameters are sent
if (!empty($_POST['job_id']) && !empty($_POST['jobseeker_id']) && !empty($_POST['status'])) {

    $job_id = trim($_POST['job_id']);
    $jobseeker_id = trim($_POST['jobseeker_id']);
    $status = trim($_POST['status']);

    // Allowed statuses for safety
    $allowed_statuses = ['shortlisted', 'interview', 'hired', 'rejected'];
    if (!in_array($status, $allowed_statuses)) {
        echo "Invalid status.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE job_id = ? AND jobseeker_id = ?");
    $stmt->bind_param("sss", $status, $job_id, $jobseeker_id);

    if ($stmt->execute()) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status.";
    }

    $stmt->close();
} else {
    echo "Missing parameters.";
}
?>
