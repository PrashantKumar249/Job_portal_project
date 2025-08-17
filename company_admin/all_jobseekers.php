<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

$query = "SELECT * FROM jobseekers";
$result = mysqli_query($conn, $query);
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">All Jobseekers</h1>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-blue-600 px-4 py-3">
                    <h2 class="text-lg font-bold text-white truncate">
                        <?= htmlspecialchars($row['name']); ?>
                    </h2>
                    <p class="text-blue-100 text-sm"><?= htmlspecialchars($row['role'] ?? 'Jobseeker'); ?></p>
                </div>

                <!-- Details -->
                <div class="p-4 space-y-2">
                    <p class="text-sm"><strong>Email:</strong> <?= htmlspecialchars($row['email']); ?></p>
                    <p class="text-sm"><strong>Phone:</strong> <?= htmlspecialchars($row['phone']); ?></p>
                    <p class="text-sm"><strong>Skills:</strong> <?= htmlspecialchars($row['skills']); ?></p>
                </div>

                <!-- Resume -->
                <div class="px-4 pb-4">
                   <?php if (!empty($row['resume'])): ?>
                     <a href="../resumes/<?= htmlspecialchars($row['resume']); ?>" 
                           target="_blank" 
                           class="inline-block bg-teal-500 hover:bg-teal-600 text-white text-xs sm:text-sm px-3 py-1.5 rounded transition w-full sm:w-auto text-center">
                       📄 View Resume
                     </a>
                  <?php else: ?>
                     <p class="text-gray-500 text-sm">No resume uploaded</p>
                  <?php endif; ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Back Button -->
    <div class="mt-10 text-center">
        <a href="dashboard.php"
           class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded transition">
            🔙 Back to Dashboard
        </a>
    </div>
</div>
