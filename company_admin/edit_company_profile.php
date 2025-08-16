<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

$admin_id = $_SESSION['company_admin_id'];

// Fetch admin details
$query = "SELECT * FROM company_admins WHERE company_admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $admin_id);
$stmt->execute();
$admin_result = $stmt->get_result();
$company_admin = $admin_result->fetch_assoc();
$stmt->close();

// Fetch company details
$query = "SELECT * FROM companies WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $company_admin['company_id']);
$stmt->execute();
$company_result = $stmt->get_result();
$company = $company_result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $company_name = $_POST['company_name'];
    $company_description = $_POST['company_description'];
    $company_website = $_POST['company_website'];
    $company_industry = $_POST['company_industry'];
    $company_address = $_POST['company_address'];
    $company_phone = $_POST['company_phone'];
    $company_email = $_POST['company_email'];

    // Handle logo upload
    if (!empty($_FILES['company_logo']['name'])) {
        $logo_name = time() . '_' . basename($_FILES['company_logo']['name']);
        $target_path = "../Uploads/company_logo/" . $logo_name;
        move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_path);
    } else {
        $logo_name = $company['logo']; // Keep existing logo
    }

    // Update admin
    $query = "UPDATE company_admins SET name=?, email=? WHERE company_admin_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $admin_name, $admin_email, $admin_id);
    $stmt->execute();
    $stmt->close();

    // Update company
    $query = "UPDATE companies 
              SET name=?, description=?, website=?, industry=?, address=?, contact_phone=?, contact_email=?, logo=? 
              WHERE company_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssss", $company_name, $company_description, $company_website, $company_industry, $company_address, $company_phone, $company_email, $logo_name, $company_admin['company_id']);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_profile.php");
    exit();
}

$conn->close();
?>

<body class="min-h-screen bg-gradient-to-b from-green-50 to-green-200 font-sans">
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8 sm:p-12 space-y-8">

            <h1 class="text-3xl font-bold text-green-800 text-center">Edit Company Profile</h1>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Logo -->
                <div>
                    <label class="block text-sm font-medium text-green-700 mb-2">Company Logo</label>
                    <?php if (!empty($company['logo'])): ?>
                        <img src="../Uploads/company_logo/<?php echo htmlspecialchars($company['logo']); ?>" class="w-20 h-20 rounded-full mb-3">
                    <?php endif; ?>
                    <input type="file" name="company_logo" class="block w-full border border-green-300 rounded-lg p-2">
                </div>

                <!-- Company Name -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Company Name</label>
                    <input type="text" name="company_name" value="<?php echo htmlspecialchars($company['name']); ?>" class="w-full border rounded-lg p-2">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Description</label>
                    <textarea name="company_description" class="w-full border rounded-lg p-2"><?php echo htmlspecialchars($company['description']); ?></textarea>
                </div>

                <!-- Website -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Website</label>
                    <input type="text" name="company_website" value="<?php echo htmlspecialchars($company['website']); ?>" class="w-full border rounded-lg p-2">
                </div>

                <!-- Industry -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Industry</label>
                    <input type="text" name="company_industry" value="<?php echo htmlspecialchars($company['industry']); ?>" class="w-full border rounded-lg p-2">
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Address</label>
                    <input type="text" name="company_address" value="<?php echo htmlspecialchars($company['address']); ?>" class="w-full border rounded-lg p-2">
                </div>

                <!-- Contact Phone -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Contact Phone</label>
                    <input type="text" name="company_phone" value="<?php echo htmlspecialchars($company['contact_phone']); ?>" class="w-full border rounded-lg p-2">
                </div>

                <!-- Contact Email -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Contact Email</label>
                    <input type="email" name="company_email" value="<?php echo htmlspecialchars($company['contact_email']); ?>" class="w-full border rounded-lg p-2">
                </div>

                <!-- Admin Name -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Admin Name</label>
                    <input type="text" name="admin_name" value="<?php echo htmlspecialchars($company_admin['name']); ?>" class="w-full border rounded-lg p-2">
                </div>

                <!-- Admin Email -->
                <div>
                    <label class="block text-sm font-medium text-green-700">Admin Email</label>
                    <input type="email" name="admin_email" value="<?php echo htmlspecialchars($company_admin['email']); ?>" class="w-full border rounded-lg p-2">
                </div>

                <!-- Save Button -->
                <div class="text-center">
                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-full font-semibold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
