<?php
session_start();
include '../inc/db.php'; // Your DB connection file

// Check if application_id is provided
if (!isset($_GET['application_id']) || empty($_GET['application_id'])) {
    die("Invalid request.");
}

$application_id = $_GET['application_id'];

// Fetch interview details
$stmt = $conn->prepare("SELECT interview_date, mode, location_or_link, notes, created_at 
                        FROM interview_schedule 
                        WHERE application_id = ?");
$stmt->bind_param("s", $application_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No interview schedule found for this application.");
}

$interview = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interview Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    
<div class="max-w-lg mx-auto mt-10 bg-white rounded-xl shadow-lg p-6">
    <h1 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Interview Details</h1>

    <div class="space-y-3 text-sm sm:text-base">
        <div class="flex justify-between">
            <span class="text-gray-500 font-medium">Date:</span>
            <span class="text-gray-900 font-semibold">
                <?php echo date("d M Y, h:i A", strtotime($interview['interview_date'])); ?>
            </span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-500 font-medium">Mode:</span>
            <span class="text-gray-900"><?php echo htmlspecialchars($interview['mode']); ?></span>
        </div>

        <div>
            <p class="text-gray-500 font-medium">Location / Link:</p>
            <p class="text-gray-900 break-words"><?php echo htmlspecialchars($interview['location_or_link']); ?></p>
        </div>

        <div>
            <p class="text-gray-500 font-medium">Notes:</p>
            <p class="text-gray-900 whitespace-pre-line"><?php echo htmlspecialchars($interview['notes']); ?></p>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-500 font-medium">Created At:</span>
            <span class="text-gray-900"><?php echo date("d M Y, h:i A", strtotime($interview['created_at'])); ?></span>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="javascript:history.back()" 
           class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
           Back
        </a>
    </div>
</div>
</body>
</html>
