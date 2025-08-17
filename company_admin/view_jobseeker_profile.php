<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

// Get jobseeker_id from URL
$jobseeker_id = isset($_GET['jobseeker_id']) ?($_GET['jobseeker_id']) : 0;
$job_id = $_GET['job_id'] ?? null;

if ($jobseeker_id <= 0) {
    echo "<div class='p-6 text-red-500 font-bold'>Invalid Jobseeker ID.</div>";
    exit();
}

// Fetch jobseeker details
$stmt = $conn->prepare("SELECT * FROM jobseekers WHERE jobseeker_id = ?");
$stmt->bind_param("s", $jobseeker_id);
$stmt->execute();
$result = $stmt->get_result();
$jobseeker = $result->fetch_assoc();

if (!$jobseeker) {
    echo "<div class='p-6 text-red-500 font-bold'>Jobseeker not found.</div>";
    exit();
}
?>

<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Jobseeker Profile</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600"><strong class="text-gray-800">Name:</strong> <?= htmlspecialchars($jobseeker['name']) ?></p>
                <p class="text-gray-600"><strong class="text-gray-800">Email:</strong> <?= htmlspecialchars($jobseeker['email']) ?></p>
                <p class="text-gray-600"><strong class="text-gray-800">Phone:</strong> <?= htmlspecialchars($jobseeker['phone']) ?></p>
                <p class="text-gray-600"><strong class="text-gray-800">Role:</strong> <?= htmlspecialchars($jobseeker['role']) ?></p>
            </div>
            <div>
                <p class="text-gray-600"><strong class="text-gray-800">Qualification:</strong> <?= htmlspecialchars($jobseeker['education']) ?></p>
                <p class="text-gray-600"><strong class="text-gray-800">Course:</strong> <?= htmlspecialchars($jobseeker['course']) ?></p>
                <p class="text-gray-600"><strong class="text-gray-800">Specialization:</strong> <?= htmlspecialchars($jobseeker['specialization']) ?></p>
                <p class="text-gray-600"><strong class="text-gray-800">Skills:</strong> <?= htmlspecialchars($jobseeker['skills']) ?></p>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Resume</h3>
            <?php if (!empty($jobseeker['resume'])): ?>
                <a href="../resumes/<?= htmlspecialchars($jobseeker['resume']) ?>" 
                   target="_blank" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                   View Resume
                </a>
            <?php else: ?>
                <p class="text-gray-500">No resume uploaded.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="mt-10 text-center">
        <a href="applicants.php?job_id=<?= urlencode($job_id); ?>"
            class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded transition">
            🔙 Back to View Applicants
        </a>
    </div>
</div>
