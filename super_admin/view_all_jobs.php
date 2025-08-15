<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'super_admin_header.php';

// Get company ID from URL
$company_id = isset($_GET['company_id']) ?$_GET['company_id'] : 0;

// If no company ID, redirect back
if ($company_id <= 0) {
    header("Location: manage_companies.php");
    exit();
}

// Fetch company details
$stmt = $conn->prepare("SELECT name FROM companies WHERE company_id = ?");
$stmt->bind_param("s", $company_id);
$stmt->execute();
$stmt->bind_result($company_name);
$stmt->fetch();
$stmt->close();

if (!$company_name) {
    echo "<div class='p-6 text-center text-red-600'>Company not found.</div>";
    exit();
}

// Fetch all jobs for this company
$stmt = $conn->prepare("SELECT job_id, title, description_title, description, skills_required, employment_type, experience_level, location, salary_min, salary_max, created_at 
                        FROM jobs 
                        WHERE company_id = ? 
                        ORDER BY created_at DESC");
$stmt->bind_param("s", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$total_jobs = $result->num_rows;
?>

<div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
        Jobs Posted by <?= htmlspecialchars($company_name); ?> 
        <span class="text-sm text-gray-500">(<?= $total_jobs; ?>)</span>
    </h2>

    <div class="overflow-x-auto bg-white rounded shadow-md">
        <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border">#</th>
                    <th class="px-4 py-3 border">Title</th>
                    <th class="px-4 py-3 border">Description Title</th>
                    <th class="px-4 py-3 border">Description</th>
                    <th class="px-4 py-3 border">Skills</th>
                    <th class="px-4 py-3 border">Job Type</th>
                    <th class="px-4 py-3 border">Experience</th>
                    <th class="px-4 py-3 border">Location</th>
                    <th class="px-4 py-3 border">Salary</th>
                    <th class="px-4 py-3 border">Posted On</th>
                    <th class="px-4 py-3 border">Applied Applicants</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total_jobs > 0): $i = 1; while ($job = $result->fetch_assoc()): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 border"><?= $i++; ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($job['title']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($job['description_title']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($job['description']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($job['skills_required']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($job['employment_type']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($job['experience_level']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($job['location']); ?></td>

                    <td class="px-4 py-2 border">
                        ₹<?= number_format($job['salary_min']); ?> - ₹<?= number_format($job['salary_max']); ?>
                    </td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($job['created_at']); ?></td>
                    <td class="px-4 py-2 border text-center">
            <a href="view_applicants.php?job_id=<?= $job['job_id']; ?>&amp;company_id=<?= urlencode($company_id); ?>" 
               class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                View
            </a>
        </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                        No jobs found for this company.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 text-center">
        <a href="manage_companies.php" 
           class="inline-block bg-white text-blue-700 border border-blue-700 px-5 py-2 rounded hover:bg-blue-700 hover:text-white transition">
            ← Back to Companies
        </a>
    </div>
</div>
