<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'super_admin_header.php';

// Get job_id from URL
$job_id = isset($_GET['job_id']) ?($_GET['job_id']) : 0;

if ($job_id <= 0) {
    echo "<div class='p-6 text-center text-red-600'>Invalid Job ID.</div>";
    exit();
}

// Fetch job title
$stmt = $conn->prepare("SELECT title FROM jobs WHERE job_id = ?");
$stmt->bind_param("s", $job_id);
$stmt->execute();
$stmt->bind_result($job_title);
$stmt->fetch();
$stmt->close();

if (!$job_title) {
    echo "<div class='p-6 text-center text-red-600'>Job not found.</div>";
    exit();
}

// Fetch applicants for this job
$sql = "
    SELECT 
        js.jobseeker_id,
        js.name AS jobseeker_name,
        js.skills,
        js.resume,
        a.status,
        a.applied_at
    FROM applications a
    JOIN jobseekers js ON a.jobseeker_id = js.jobseeker_id
    WHERE a.job_id = ?
    ORDER BY a.applied_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$total_applicants = $result->num_rows;
?>

<div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
        Applicants for <?= htmlspecialchars($job_title) ?>
        <span class="text-sm text-gray-500">(<?= $total_applicants; ?>)</span>
    </h2>

    <div class="overflow-x-auto bg-white rounded shadow-md">
        <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border">#</th>
                    <th class="px-4 py-3 border">Name</th>
                    <th class="px-4 py-3 border">Skills</th>
                    <th class="px-4 py-3 border">Resume</th>
                    <th class="px-4 py-3 border">Status</th>
                    <th class="px-4 py-3 border">Applied On</th>
                    <th class="px-4 py-3 border">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total_applicants > 0): $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 border"><?= $i++; ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['jobseeker_name']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['skills']); ?></td>
                    <td class="px-4 py-2 border">
                        <?php if (!empty($row['resume'])): ?>
                          <a href="../resumes/<?= htmlspecialchars($row['resume']); ?>" target="_blank" 
                             class="bg-blue-500 hover:bg-blue-600 text-white 
                                  px-2 py-1 text-xs rounded block text-center 
                                  sm:inline-block sm:px-3 sm:py-1.5 sm:text-sm">
                             View Resume
                          </a>
                        <?php else: ?>
                          <span class="text-gray-500">No Resume</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-2 border"><?= ucfirst($row['status']); ?></td>
                    <td class="px-4 py-2 border"><?= date("d M Y, h:i A", strtotime($row['applied_at'])); ?></td>
                    <td class="px-4 py-2 border">
                        <a href="view_jobseeker_profile.php?jobseeker_id=<?= urlencode($row['jobseeker_id']);?>&amp;job_id=<?= urlencode($job_id); ?>" 
                           class="bg-green-500 hover:bg-green-600 text-white 
                              px-2 py-1 text-xs rounded block text-center 
                              sm:inline-block sm:px-3 sm:py-1.5 sm:text-sm">
                           View Profile
                        </a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                        No applicants found for this job.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 text-center">
        <a href="view_all_jobs.php" 
           class="inline-block bg-white text-blue-700 border border-blue-700 px-5 py-2 rounded hover:bg-blue-700 hover:text-white transition">
            ← Back to Jobs
        </a>
    </div>
</div>
