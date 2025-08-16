<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'super_admin_header.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Pehle company_id nikal lo
    $stmt = $conn->prepare("SELECT company_id FROM company_admins WHERE company_admin_id = ?");
    $stmt->bind_param("s", $delete_id);
    $stmt->execute();
    $stmt->bind_result($company_id);
    $stmt->fetch();
    $stmt->close();

    if ($company_id) {
        // Company Admin ko delete karo
        $stmt = $conn->prepare("DELETE FROM company_admins WHERE company_admin_id = ?");
        $stmt->bind_param("s", $delete_id);
        $stmt->execute();
        $stmt->close();

        // Company ko delete karo
        $stmt = $conn->prepare("DELETE FROM companies WHERE company_id = ?");
        $stmt->bind_param("s", $company_id);
        $stmt->execute();
        $stmt->close();

        $message = "✅ Company admin and associated company deleted successfully.";
    } else {
        $message = "❌ Company admin not found.";
    }
}

// Fetch all company admins with company names
$sql = "
    SELECT ca.company_admin_id, ca.name AS admin_name, c.name AS company_name
    FROM company_admins ca
    LEFT JOIN companies c ON ca.company_id = c.company_id
    ORDER BY ca.company_admin_id DESC
";
$result = $conn->query($sql);
?>

<body class="bg-gray-100 p-4 sm:p-6">

    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Manage Company Admins</h1>

        <?php if (!empty($message)): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-300">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Responsive Table Container -->
        <div class="bg-white shadow rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-200 text-sm sm:text-base">
                   <thead class="bg-gray-200">
                     <tr>
                       <th class="px-4 py-2 border text-left">ID</th>
                       <th class="px-4 py-2 border text-left">Admin Name</th>
                       <th class="px-4 py-2 border text-left">Company Name</th>
                       <th class="px-4 py-2 border text-center">Post Job</th> <!-- New Column -->
                       <th class="px-4 py-2 border text-center">Action</th>
                     </tr>
                   </thead>
                   <tbody>
                      <?php while ($row = $result->fetch_assoc()): ?>
                       <tr class="hover:bg-gray-100">
                         <td class="px-4 py-2 border"><?php echo $row['company_admin_id']; ?></td>
                         <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['admin_name']); ?></td>
                         <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['company_name'] ?? 'No Company'); ?></td>
            
                        <!-- Responsive Post Job Button -->
                        <td class="px-4 py-2 border text-center">
                            <a href="admin_post_job.php?admin_id=<?= urlencode($row['company_admin_id']); ?>" 
                               class="bg-blue-500 hover:bg-blue-600 text-white 
                                    px-2 py-1 text-xs rounded block text-center 
                                    sm:inline-block sm:px-3 sm:py-1.5 sm:text-sm transition">
                               Post Job
                            </a>
                        </td>


                         <td class="px-4 py-2 border text-center">
                            <a href="?delete_id=<?php echo $row['company_admin_id']; ?>"
                             class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition"
                              onclick="return confirm('Are you sure you want to delete this company admin and its company?');">
                                Delete
                            </a>
                         </td>
                       </tr>
                   <?php endwhile; ?>
                 </tbody>

                </table>
            </div>
        </div>
        <!-- Back to Dashboard -->
        <div class="mt-6 text-center">
            <a href="dashboard.php"
                class="inline-block bg-white text-blue-700 border border-blue-700 px-5 py-2 rounded hover:bg-blue-700 hover:text-white transition">
                ← Back to Dashboard
            </a>
        </div>
    </div>

</body>