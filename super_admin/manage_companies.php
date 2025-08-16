<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'super_admin_header.php';

// Fetch all companies
$sql = "SELECT * FROM companies ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">

    <!-- Add Company Button -->
    <div class="mb-4 flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Manage Companies</h2>
        <a href="add_company.php" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition">
            + Add New Company
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded shadow-md">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border">#</th>
                    <th class="px-4 py-3 border">Company Name</th>
                    <th class="px-4 py-3 border">Industry</th>
                    <th class="px-4 py-3 border">Email</th>
                    <th class="px-4 py-3 border">Created At</th>
                    <th class="px-4 py-3 border">View All Jobs</th>
                    <th class="px-4 py-3 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 border"><?= $i++; ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['name']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['industry']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['contact_email']); ?></td>
                    <td class="px-4 py-2 border"><?= $row['created_at']; ?></td>
                    <td class="px-4 py-2 border text-center">
                        <a href="view_all_jobs.php?company_id=<?= $row['company_id']; ?>" 
                            class="bg-blue-600 text-white px-3 py-1 text-sm rounded 
                                  hover:bg-blue-700 transition 
                                  sm:px-4 sm:py-2 sm:text-base block text-center">
                            View Jobs
                        </a>
                    </td>
                    <td class="px-4 py-2 border whitespace-nowrap">
                        <a href="delete_company.php?id=<?= $row['company_id']; ?>" 
                           class="text-red-600 hover:underline" 
                           onclick="return confirm('Are you sure you want to delete this company?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">No companies found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-6 text-center">
        <a href="dashboard.php" class="inline-block bg-white text-blue-700 border border-blue-700 px-5 py-2 rounded hover:bg-blue-700 hover:text-white transition">
            ← Back to Dashboard
        </a>
    </div>

</div>
