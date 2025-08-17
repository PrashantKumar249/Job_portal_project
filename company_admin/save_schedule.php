<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

include '../inc/db.php';

if (
    !empty($_POST['application_id']) &&
    !empty($_POST['interview_date']) &&
    !empty($_POST['mode']) &&
    !empty($_POST['location_or_link'])
) {
    $application_id = $_POST['application_id'];
    $interview_date = $_POST['interview_date'];
    $mode = strtolower(trim($_POST['mode'])); // convert to lowercase
    $location_or_link = $_POST['location_or_link'];
    $notes = $_POST['notes'] ?? '';

    // Validate mode
    if (!in_array($mode, ['online', 'offline'])) {
        echo "Invalid mode.";
        exit();
    }
    $interview_id = $interview_id = 'IN' . rand(1000, 9999);

    // Add created_at with NOW()
    $stmt = $conn->prepare("
    INSERT INTO interview_schedule 
    (interview_id, application_id, interview_date, company_admin_id, mode, location_or_link, notes, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
");

    $stmt->bind_param("sssssss", $interview_id, $application_id, $interview_date, $_SESSION['company_admin_id'], $mode, $location_or_link, $notes);


    if ($stmt->execute()) {
        echo "Interview scheduled successfully.";
    } else {
        echo "Error scheduling interview: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Missing required fields.";
}
