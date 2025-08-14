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
    $delete_id = (int) $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM company_admins WHERE company_admin_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Company admin deleted successfully.";
    } else {
        $message = "Error deleting company admin.";
    }
    $stmt->close();
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
<!DOCTYPE html>
<html>
<head>
    <title>Manage Company Admins</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

    <h1 class="text-2xl font-bold mb-4">Manage Company Admins</h1>

    <?php if (!empty($message)): ?>
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Admin Name</th>
                    <th class="px-4 py-2 border">Company Name</th>
                    <th class="px-4 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border text-center"><?php echo $row['company_admin_id']; ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['admin_name']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['company_name'] ?? 'No Company'); ?></td>
                        <td class="px-4 py-2 border text-center">
                            <a href="?delete_id=<?php echo $row['company_admin_id']; ?>" 
                               class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                               onclick="return confirm('Are you sure you want to delete this company admin?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
