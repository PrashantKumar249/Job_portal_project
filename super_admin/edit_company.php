<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'super_admin_header.php';

$message = '';

// Validate company_id
if (!isset($_GET['company_id']) || empty($_GET['company_id'])) {
    die("❌ Invalid Company ID.");
}

$company_id = $_GET['company_id'];

// Fetch existing company and admin details
$stmt = $conn->prepare("
    SELECT c.name, c.industry, c.logo, c.contact_email, c.contact_phone, c.address, c.description, c.website,
           a.company_admin_id, a.name AS admin_name, a.email AS admin_email, a.password AS admin_password
    FROM companies c
    LEFT JOIN company_admins a ON c.company_id = a.company_id
    WHERE c.company_id = ?
");
$stmt->bind_param("s", $company_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Company not found.");
}

$row = $result->fetch_assoc();
$stmt->close();

// Assign variables
$name = $row['name'];
$industry = $row['industry'];
$logo = $row['logo'];
$email = $row['contact_email'];
$phone = $row['contact_phone'];
$address = $row['address'];
$description = $row['description'];
$website = $row['website'];

$admin_id = $row['company_admin_id'];
$admin_name = $row['admin_name'];
$admin_email = $row['admin_email'];
$admin_password = $row['admin_password'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Updated Company details
    $name = $_POST['name'];
    $industry = $_POST['industry'];
    $logo = $_POST['logo'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $website = $_POST['website'];

    // Updated Admin details
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Start transaction
    $conn->begin_transaction();
    try {
        // Update companies table
        $stmt1 = $conn->prepare("UPDATE companies SET name=?, industry=?, logo=?, contact_email=?, contact_phone=?, address=?, description=?, website=? WHERE company_id=?");
        $stmt1->bind_param("sssssssss", $name, $industry, $logo, $email, $phone, $address, $description, $website, $company_id);
        $stmt1->execute();
        $stmt1->close();

        // Update company_admins table if exists
        if ($admin_id) {
            $stmt2 = $conn->prepare("UPDATE company_admins SET name=?, email=?, password=? WHERE company_admin_id=?");
            $stmt2->bind_param("ssss", $admin_name, $admin_email, $admin_password, $admin_id);
            $stmt2->execute();
            $stmt2->close();
        }

        $conn->commit();
        $message = "✅ Company & Admin updated successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>

<!-- Main Content -->
<div class="max-w-4xl mx-auto p-6 sm:p-10">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Company</h1>
            <p class="text-sm text-gray-500 mt-1">Update company & admin details below.</p>
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
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Industry</label>
            <input type="text" name="industry" value="<?= htmlspecialchars($industry) ?>" class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Logo Filename</label>
            <input type="text" name="logo" value="<?= htmlspecialchars($logo) ?>" class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Website</label>
            <input type="text" name="website" value="<?= htmlspecialchars($website) ?>" class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Address</label>
            <textarea name="address" rows="2" class="w-full px-4 py-2 border rounded-md shadow-sm resize-none"><?= htmlspecialchars($address) ?></textarea>
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-md shadow-sm resize-none"><?= htmlspecialchars($description) ?></textarea>
        </div>

        <!-- Admin Details -->
        <h2 class="text-xl font-bold text-gray-700 mt-6">Company Admin Details</h2>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Admin Name *</label>
            <input type="text" name="admin_name" value="<?= htmlspecialchars($admin_name) ?>" required class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Admin Email *</label>
            <input type="email" name="admin_email" value="<?= htmlspecialchars($admin_email) ?>" required class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Password *</label>
            <input type="text" name="admin_password" value="<?= htmlspecialchars($admin_password) ?>" required class="w-full px-4 py-2 border rounded-md shadow-sm" />
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-lg shadow-md hover:bg-blue-700 transition">
            ✏️ Update Company & Admin
        </button>
    </form>

    <div class="mt-8 text-center">
        <a href="dashboard.php" class="inline-block bg-gray-100 text-gray-700 px-5 py-2 rounded-lg shadow hover:bg-gray-200 transition">
            ← Back to Dashboard
        </a>
    </div>
</div>
