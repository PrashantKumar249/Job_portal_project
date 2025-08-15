<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'super_admin_header.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Company details
    $company_id = "CMP" . rand(1000, 9999);
    $name = $_POST['name'];
    $industry = $_POST['industry'];
    $logo = isset($_POST['logo']) ? $_POST['logo'] : '';
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $website = $_POST['website'];

    // Company Admin details
    $company_admin_id = "admin" . rand(1000, 9999);
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password']; // plain password (no hash)
    $created_at = date("Y-m-d H:i:s");

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into companies table
        $stmt1 = $conn->prepare("INSERT INTO companies (company_id, name, industry, logo, contact_email, contact_phone, address, description, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt1->bind_param("sssssssss", $company_id, $name, $industry, $logo, $email, $phone, $address, $description, $website);
        $stmt1->execute();

        // Insert into company_admins table
        $stmt2 = $conn->prepare("INSERT INTO company_admins (company_admin_id, company_id, name, email, password, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("ssssss", $company_admin_id, $company_id, $admin_name, $admin_email, $admin_password, $created_at);
        $stmt2->execute();

        // Commit
        $conn->commit();
        $message = "✅ Company & Admin added successfully!";

    } catch (Exception $e) {
        // Rollback if error
        $conn->rollback();
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>

<!-- Main Content -->
<div class="max-w-4xl mx-auto p-6 sm:p-10">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">➕ Add New Company</h1>
            <p class="text-sm text-gray-500 mt-1">Enter company & admin details to register a new organization.</p>
        </div>
        <a href="manage_companies.php" class="text-sm text-blue-600 hover:underline">← Back to Company List</a>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded bg-green-100 border border-green-400 text-green-800 shadow">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white shadow-lg rounded-xl p-6 space-y-5">
        <!-- Company Details -->
        <h2 class="text-xl font-bold text-gray-700">Company Details</h2>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Company Name *</label>
            <input type="text" name="name" required class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Industry</label>
            <input type="text" name="industry" class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Logo Filename (e.g., logo.png)</label>
            <input type="text" name="logo" class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Email *</label>
            <input type="email" name="email" required class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Phone</label>
            <input type="text" name="phone" class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Website</label>
            <input type="text" name="website" class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Address</label>
            <textarea name="address" rows="2" class="w-full px-4 py-2 border rounded-md shadow-sm resize-none"></textarea>
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-md shadow-sm resize-none"></textarea>
        </div>

        <!-- Admin Details -->
        <h2 class="text-xl font-bold text-gray-700 mt-6">Company Admin Details</h2>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Admin Name *</label>
            <input type="text" name="admin_name" required class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Admin Email *</label>
            <input type="email" name="admin_email" required class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Password *</label>
            <input type="text" name="admin_password" required class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-lg shadow-md hover:bg-blue-700 transition">
            ➕ Add Company & Admin
        </button>
    </form>

    <!-- Back to Dashboard -->
    <div class="mt-8 text-center">
        <a href="dashboard.php" class="inline-block bg-gray-100 text-gray-700 px-5 py-2 rounded-lg shadow hover:bg-gray-200 transition">
            ← Back to Dashboard
        </a>
    </div>
</div>
