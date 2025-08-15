<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php'; // Database connection

$message = '';
$error = '';

// Get company ID from URL
$company_id = $_GET['id'] ?? null;

if ($company_id) {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete company admins first (if any)
        $stmt1 = $conn->prepare("DELETE FROM company_admins WHERE company_id = ?");
        $stmt1->bind_param("s", $company_id);
        $stmt1->execute();
        $stmt1->close();

        // Delete the company
        $stmt2 = $conn->prepare("DELETE FROM companies WHERE company_id = ?");
        $stmt2->bind_param("s", $company_id);
        $stmt2->execute();
        $stmt2->close();

        $conn->commit();
        $message = "✅ Company deleted successfully.";

    } catch (Exception $e) {
        $conn->rollback();
        $error = "❌ Failed to delete company.";
    }

} else {
    $error = "⚠️ Invalid company ID.";
}
?>

<!-- Result UI -->
<div class="max-w-xl mx-auto mt-16 px-4">
    <div class="bg-white shadow-md rounded-lg p-6 text-center space-y-4">
        <h2 class="text-xl font-bold text-gray-800">Delete Company</h2>
        <?php if($message): ?>
            <p class="text-green-700"><?= htmlspecialchars($message) ?></p>
        <?php elseif($error): ?>
            <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="flex justify-center gap-4 mt-6">
            <a href="manage_companies.php"
               class="px-5 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
               ← Back to Company List
            </a>
            <a href="dashboard.php" class="px-5 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
               Dashboard
            </a>
        </div>
    </div>
</div>
