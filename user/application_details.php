<?php
// application_details.php
session_start();
include '../inc/db.php';
include 'include/header.php';

// Ensure user is logged in
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: login.php");
    exit();
}

$jobseeker_id = $_SESSION['jobseeker_id'];

// Ensure application_id is passed
if (!isset($_GET['application_id'])) {
    echo "<p class='text-red-500 text-center mt-6'>Invalid request.</p>";
    exit();
}

$application_id = $_GET['application_id'];

// Fetch application + job info
$sql = "SELECT a.application_id, a.job_id, a.status, a.applied_at, 
               j.title, j.description
        FROM applications a
        JOIN jobs j ON a.job_id = j.job_id
        WHERE a.application_id = '$application_id'";
       

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<p class='text-red-500 text-center mt-6'>Application not found.</p>";
    exit();
}

$app = mysqli_fetch_assoc($result);
?>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Application Details</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <p><strong>Application ID:</strong> <?= $app['application_id'] ?></p>
            <p><strong>Job ID:</strong> <?= $app['job_id'] ?></p>
            <p><strong>Job Title:</strong> <?= htmlspecialchars($app['title']) ?></p>
            <p><strong>Status:</strong> 
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                    <?= $app['status'] == 'Pending' ? 'bg-yellow-100 text-yellow-700' : 
                       ($app['status'] == 'Accepted' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') ?>">
                    <?= $app['status'] ?>
                </span>
            </p>
            <p><strong>Applied At:</strong> <?= date("d M Y, h:i A", strtotime($app['applied_at'])) ?></p>

            <hr class="my-4">

            <h2 class="text-lg font-semibold mb-2">Job Description</h2>
            <p class="text-gray-700 whitespace-pre-line"><?= nl2br(htmlspecialchars($app['description'])) ?></p>
        </div>

        <div class="mt-4">
            <a href="applied_jobs.php" class="text-blue-600 hover:underline">← Back to Applied Jobs</a>
        </div>
    </div>
    <?php include('include/footer.php'); ?>
</body>
