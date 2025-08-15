<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php'; 
include 'super_admin_header.php'; 

$job_id = $_GET['job_id'] ?? null;


// Jobseeker ID from URL
$jobseeker_id = $_GET['jobseeker_id'] ?? null;

if (!$jobseeker_id) {
    echo "<div class='p-6 text-center text-red-600'>No jobseeker selected.</div>";
    exit();
}

// Fetch jobseeker details
$stmt = $conn->prepare("SELECT name, email, phone, education, course, specialization, experience_level, role, skills, resume, created_at 
                        FROM jobseekers 
                        WHERE jobseeker_id = ?");
$stmt->bind_param("s", $jobseeker_id);
$stmt->execute();
$result = $stmt->get_result();
$jobseeker = $result->fetch_assoc();

if (!$jobseeker) {
    echo "<div class='p-6 text-center text-red-600'>Profile not found.</div>";
    exit();
}
?>

<div class="max-w-5xl mx-auto p-6">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white"><?= htmlspecialchars($jobseeker['name']); ?></h2>
            <p class="text-blue-100"><?= htmlspecialchars($jobseeker['role'] ?? 'Jobseeker'); ?></p>
        </div>

        <!-- Profile Details -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Contact Information</h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($jobseeker['email']); ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($jobseeker['phone']); ?></p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Education</h3>
                <p><strong>Qualification:</strong> <?= htmlspecialchars($jobseeker['education']); ?></p>
                <p><strong>Course:</strong> <?= htmlspecialchars($jobseeker['course']); ?></p>
                <p><strong>Specialization:</strong> <?= htmlspecialchars($jobseeker['specialization']); ?></p>
                <p><strong>Experience:</strong> <?= htmlspecialchars($jobseeker['experience_level']); ?></p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Skills</h3>
                <p><?= htmlspecialchars($jobseeker['skills']); ?></p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Resume</h3>
                <?php if (!empty($jobseeker['resume'])): ?>
                    <a href="../resumes/<?= htmlspecialchars($jobseeker['resume']); ?>" 
                       class="text-blue-600 hover:underline" target="_blank">
                        View resume
                    </a>
                <?php else: ?>
                    <p class="text-gray-500">No resume uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
            <p class="text-sm text-gray-500">Member since <?= date("F j, Y", strtotime($jobseeker['created_at'])); ?></p>
        </div>
    </div>
<div class="mt-6 text-center">
    <a href="view_applicants.php?job_id=<?= urlencode($job_id); ?>" 
       class="inline-block bg-white text-blue-700 border border-blue-700 px-5 py-2 rounded hover:bg-blue-700 hover:text-white transition">
        ← Back
    </a>
</div>

</div>
