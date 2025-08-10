<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

$company_id = $_SESSION['company_id'];
$admin_id = $_SESSION['company_admin_id'];

// Fetch jobs posted by this admin
$sql = "SELECT * FROM jobs WHERE company_id = ? AND posted_by = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $company_id, $admin_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">🗂️ Manage Job Listings</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="bg-white rounded-xl border p-5 shadow hover:shadow-md transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-1"><?= htmlspecialchars($row['title']) ?></h2>
                    <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($row['description_title']) ?></p>
                    <p class="text-sm text-gray-500"><strong>Status:</strong> <span
                            class="capitalize"><?= $row['status'] ?></span></p>
                    <p class="text-sm text-gray-500"><strong>Deadline:</strong> <?= $row['deadline'] ?></p>
                    <p class="text-sm text-gray-500"><strong>Salary:</strong> ₹<?= $row['salary_min'] ?> -
                        ₹<?= $row['salary_max'] ?></p>
                    <p class="text-sm text-gray-500 mb-4"><strong>Type:</strong> <?= ucfirst($row['employment_type']) ?> |
                        <strong>Experience:</strong> <?= ucfirst($row['experience_level']) ?></p>

                    <div class="flex justify-between items-center">
                        <a href="edit_job.php?job_id=<?= $row['job_id'] ?>" class="text-blue-600 hover:underline font-medium">✏️
                            Edit</a>
                        <!-- view applications -->
                        <a href="applicants.php?job_id=<?= $row['job_id'] ?>"
                            class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition">
                            View Applications
                        </a>

                        <a href="delete_job.php?job_id=<?= $row['job_id'] ?>" class="text-red-600 hover:underline font-medium"
                            onclick="return confirm('Are you sure you want to delete this job?')">🗑️ Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-600 text-lg">No job listings found. <a href="post_job.php"
                class="text-green-600 hover:underline">Post your first job</a>.</p>
    <?php endif; ?>

    <div class="mt-10 text-center">
        <a href="dashboard.php"
            class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded transition">
            🔙 Back to Dashboard
        </a>
    </div>

</div>