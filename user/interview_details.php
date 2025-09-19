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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen px-4">
    <div class="max-w-xl w-full mx-auto bg-white rounded-2xl shadow-xl p-8 transform transition-transform duration-300 hover:scale-[1.01]">
        <div class="text-center mb-6">
            <svg class="h-16 w-16 mx-auto mb-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h1 class="text-3xl font-bold text-gray-800">Interview Scheduled</h1>
            <p class="text-gray-500 text-sm mt-1">Details for your upcoming interview</p>
        </div>

        <div class="space-y-6">
            <div class="flex items-center space-x-4 p-4 rounded-xl bg-gray-100">
                <svg class="h-6 w-6 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div class="flex flex-col flex-grow">
                    <span class="text-gray-500 text-sm">Date & Time:</span>
                    <span class="text-gray-900 font-semibold text-lg"><?= date("d M Y, h:i A", strtotime($interview['interview_date'])); ?></span>
                </div>
            </div>

            <div class="flex items-start space-x-4 p-4 rounded-xl bg-gray-100">
                <svg class="h-6 w-6 text-gray-500 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.79 17.79a9 9 0 11-13.9 0L2 22h20L18.79 17.79z" />
                </svg>
                <div class="flex-grow">
                    <p class="text-gray-500 text-sm">Mode:</p>
                    <span class="text-gray-900 font-medium"><?= htmlspecialchars($interview['mode']); ?></span>
                    <p class="text-gray-500 text-sm mt-2">Location / Link:</p>
                    <a href="<?= htmlspecialchars($interview['location_or_link']); ?>" target="_blank" class="text-blue-600 hover:underline break-words">
                        <?= htmlspecialchars($interview['location_or_link']); ?>
                    </a>
                </div>
            </div>

            <div class="bg-gray-100 rounded-xl p-4">
                <p class="text-gray-500 text-sm font-medium mb-1">Notes:</p>
                <div class="p-3 bg-white rounded-lg border border-gray-200">
                    <p class="text-gray-800 text-sm whitespace-pre-line leading-relaxed"><?= htmlspecialchars($interview['notes']); ?></p>
                </div>
            </div>

            <div class="flex justify-between items-center text-sm p-2">
                <span class="text-gray-500 font-medium">Created At:</span>
                <span class="text-gray-900"><?= date("d M Y, h:i A", strtotime($interview['created_at'])); ?></span>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="javascript:history.back()" 
               class="px-8 py-3 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition transform hover:scale-105 shadow-lg">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Go Back
            </a>
        </div>
    </div>
</body>
</html>
