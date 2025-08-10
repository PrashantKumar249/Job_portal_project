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

// Fetch company admin details
$query = "SELECT * FROM company_admins WHERE company_admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $admin_id);
$stmt->execute();
$admin_result = $stmt->get_result();
$company_admin = $admin_result->fetch_assoc();
$stmt->close();

// Fetch company details linked to admin
$query = "SELECT * FROM companies WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $company_admin['company_id']);
$stmt->execute();
$company_result = $stmt->get_result();
$company = $company_result->fetch_assoc();
$stmt->close();

$conn->close();
?>

<body class="min-h-screen bg-gradient-to-b from-green-50 to-green-200 font-sans">
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-xl p-8 sm:p-12 space-y-8">

            <!-- Company Profile Header -->
            <div class="flex flex-col items-center text-center space-y-4">
                <?php if (!empty($company['logo'])): ?>
                    <div class="relative">
                        <img src="../Uploads/company_logos/<?php echo htmlspecialchars($company['logo']); ?>" 
                            alt="Company Logo" 
                            class="w-28 h-28 sm:w-32 sm:h-32 object-cover rounded-full border-4 border-green-300 shadow-lg">
                    </div>
                <?php endif; ?>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-green-800">
                    <?php echo htmlspecialchars($company['name']); ?>
                </h1>

                <?php if (!empty($company['description'])): ?>
                    <p class="text-gray-600 max-w-2xl text-sm sm:text-base">
                        <?php echo htmlspecialchars($company['description']); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Admin Name -->
                <div class="bg-green-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition">
                    <p class="text-sm text-green-600 font-medium">Admin Name</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2">
                        <?php echo htmlspecialchars($company_admin['name']); ?>
                    </h3>
                </div>

                <!-- Admin Email -->
                <div class="bg-green-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition">
                    <p class="text-sm text-green-600 font-medium">Admin Email</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2">
                        <?php echo htmlspecialchars($company_admin['email']); ?>
                    </h3>
                </div>

                <!-- Admin Password -->
                <div class="bg-green-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition">
                    <p class="text-sm text-green-600 font-medium">Password</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2">********</h3>
                </div>

                <!-- Website -->
                <div class="bg-green-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition">
                    <p class="text-sm text-green-600 font-medium">Website</p>
                    <a href="<?php echo htmlspecialchars($company['website']); ?>" 
                       target="_blank" 
                       class="text-green-700 font-semibold hover:underline mt-2 inline-block">
                        <?php echo htmlspecialchars($company['website']); ?>
                    </a>
                </div>

                <!-- Industry -->
                <div class="bg-green-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition">
                    <p class="text-sm text-green-600 font-medium">Industry</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2">
                        <?php echo htmlspecialchars($company['industry']); ?>
                    </h3>
                </div>

                <!-- Address -->
                <div class="bg-green-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition">
                    <p class="text-sm text-green-600 font-medium">Address</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2">
                        <?php echo htmlspecialchars($company['address']); ?>
                    </h3>
                </div>

                <!-- Contact Phone -->
                <div class="bg-green-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition">
                    <p class="text-sm text-green-600 font-medium">Contact Phone</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2">
                        <?php echo htmlspecialchars($company['contact_phone']); ?>
                    </h3>
                </div>

                <!-- Contact Email -->
                <div class="bg-green-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition">
                    <p class="text-sm text-green-600 font-medium">Contact Email</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2">
                        <?php echo htmlspecialchars($company['contact_email']); ?>
                    </h3>
                </div>
            </div>

            <!-- Edit Profile Button -->
            <div class="text-center">
                <a href="edit_company_profile.php" 
                   class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-lg font-semibold px-8 py-3 rounded-full transition-all shadow-md">
                    Edit Company Profile
                </a>
            </div>

        </div>
    </div>
</body>
</html>
