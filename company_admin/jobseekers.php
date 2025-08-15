<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

$query = "SELECT * FROM jobseekers";
$result = mysqli_query($conn, $query);
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">All Jobseekers</h1>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition duration-300 flex flex-col">
                <div class="flex items-center space-x-4">
                    <img src="<?php echo !empty($row['profile_pic']) ? '../uploads/profile/' . $row['profile_pic'] : 'https://via.placeholder.com/80'; ?>"
                        alt="Profile Picture" class="w-20 h-20 rounded-full border-2 border-blue-500 object-cover">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($row['name']); ?></h2>
                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($row['email']); ?></p>
                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($row['phone']); ?></p>
                    </div>
                </div>

                <div class="mt-4 flex-grow">
                    <p class="text-gray-700 text-sm">
                        <span class="font-medium">Skills:</span>
                        <?php echo htmlspecialchars($row['skills'] ?? ''); ?>
                    </p>
                    <p class="text-gray-700 text-sm mt-1">
                        <span class="font-medium text-blue-600">Role:</span>
                        <?php echo htmlspecialchars($row['role'] ?? 'N/A'); ?>
                    </p>
                </div>


                <div class="mt-4 flex space-x-2">
                    <a href="view_jobseeker.php?jobseeker_id=<?php echo $row['jobseeker_id']; ?>"
                        class="flex-1 text-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition">
                        View Profile
                    </a>
                    <a href="../resumes/<?php echo $row['resume']; ?>" target="_blank"
                        class="flex-1 text-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition">
                        View Resume
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>