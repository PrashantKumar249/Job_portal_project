<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'super_admin_header.php';

// Fetch all jobseekers
$sql = "SELECT jobseeker_id, name, email, phone, role, created_at FROM jobseekers ORDER BY created_at DESC";
$result = $conn->query($sql);
$total_jobseekers = $result->num_rows;
?>

<div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
        All Jobseekers
        <span class="text-sm text-gray-500">(<?= $total_jobseekers; ?>)</span>
    </h2>

    <div class="overflow-x-auto bg-white rounded shadow-md">
        <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border">#</th>
                    <th class="px-4 py-3 border">Name</th>
                    <th class="px-4 py-3 border">Email</th>
                    <th class="px-4 py-3 border">Phone</th>
                    <th class="px-4 py-3 border">Role</th>
                    <th class="px-4 py-3 border">Joined On</th>
                    <th class="px-4 py-3 border">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total_jobseekers > 0): $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 border"><?= $i++; ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['name']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['email']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['phone']); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($row['role']); ?></td>
                    <td class="px-4 py-2 border"><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                    <td class="px-4 py-2 border">
                      <div class="flex gap-3">
                        <!-- Profile button -->
                        <a href="view_jobseeker_profile.php?jobseeker_id=<?= urlencode($row['jobseeker_id']); ?>" 
                          class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                            Profile
                        </a>

                        <!-- Delete button -->
                        <a href="delete_jobseeker.php?jobseeker_id=<?= urlencode($row['jobseeker_id']); ?>" 
                           onclick="return confirm('Are you sure you want to delete this jobseeker?');"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                            Delete
                        </a>
                      </div>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                        No jobseekers found.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
     <div class="mt-6 text-center">
            <a href="dashboard.php"
                class="inline-block bg-white text-blue-700 border border-blue-700 px-5 py-2 rounded hover:bg-blue-700 hover:text-white transition">
                ← Back to Dashboard
            </a>
     </div>
</div>
