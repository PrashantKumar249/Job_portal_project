<?php
include 'super_admin_header.php';
include '../inc/db.php'; // Adjust path as needed

$company_id = $_GET['id'] ?? null;

if ($company_id) {
    $stmt = $conn->prepare("DELETE FROM companies WHERE company_id = ?");
    $stmt->bind_param("s", $company_id);

    if ($stmt->execute()) {
        $message = "✅ Company deleted successfully.";
    } else {
        $message = "❌ Failed to delete company. Try again.";
    }

    $stmt->close();
} else {
    $message = "⚠️ Invalid request.";
}
?>

<!-- Result UI -->
<div class="max-w-xl mx-auto mt-16 px-4">
    <div class="bg-white shadow-md rounded-lg p-6 text-center space-y-4">
        <h2 class="text-xl font-bold text-gray-800">Delete Company</h2>
        <p class="text-gray-600"><?= htmlspecialchars($message) ?></p>

        <div class="flex justify-center gap-4 mt-6">
            <a href="manage_companies.php" class="px-5 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                ← Back to Company List
            </a>
            <a href="dashboard.php" class="px-5 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                Dashboard
            </a>
        </div>
    </div>
</div>
</body>
</html>
