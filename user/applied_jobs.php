<?php
// applied_jobs.php
session_start();
include '../inc/db.php';
include 'include/header.php';

// Check login
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: login.php");
    exit();
}

$jobseeker_id = $_SESSION['jobseeker_id'];

// Fetch applied jobs
$sql = "SELECT application_id, status, applied_at 
        FROM applications 
        WHERE jobseeker_id = '$jobseeker_id'
        ORDER BY applied_at DESC";
$result = mysqli_query($conn, $sql);
?>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto p-4 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold mb-4 text-gray-800">My Applied Jobs</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Responsive Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 sm:px-6 py-3">Application ID</th>
                            <th class="px-4 sm:px-6 py-3">Status</th>
                            <th class="px-4 sm:px-6 py-3">Applied At</th>
                            <th class="px-4 sm:px-6 py-3 text-center">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0) { ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 sm:px-6 py-4 font-medium text-gray-900">
                                        <?php echo $row['application_id']; ?>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                        <?php if (strtolower($row['status']) === 'interview') { ?>
                                            <a href="interview_details.php?application_id=<?php echo $row['application_id']; ?>"
                                               class="mt-2 sm:mt-0 ml-0 sm:ml-2 inline-block px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                                                Interview Details
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <?php echo date("d M Y, h:i A", strtotime($row['applied_at'])); ?>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <a href="application_details.php?application_id=<?php echo $row['application_id']; ?>"
                                           class="text-blue-600 hover:underline">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" class="px-4 sm:px-6 py-4 text-center text-gray-500">
                                    No applications found.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include('include/footer.php'); ?>
</body>
