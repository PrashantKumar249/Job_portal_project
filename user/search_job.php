<?php
session_start();
include '../inc/db.php';

// Get saved jobs for current logged-in user
$saved_job = [];
if (isset($_SESSION['jobseeker_id'])) {
    $jobseeker_id = $_SESSION['jobseeker_id'];
    $res = mysqli_query($conn, "SELECT job_id FROM saved_jobs WHERE jobseeker_id = '$jobseeker_id'");
    while ($row = mysqli_fetch_assoc($res)) {
        $saved_job[] = $row['job_id'];
    }
}

// Search parameters
$job = isset($_POST['job']) ? mysqli_real_escape_string($conn, $_POST['job']) : '';
$location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : '';

// Query with JOIN to get company name & logo
$sql = "SELECT j.*, c.name, c.logo
        FROM jobs j
        LEFT JOIN companies c ON j.company_id = c.company_id
        WHERE 1";

if (!empty($job)) {
    $sql .= " AND (j.title LIKE '%$job%' 
              OR c.name LIKE '%$job%' 
              OR j.description LIKE '%$job%' 
              OR j.skills_required LIKE '%$job%')";
}

if (!empty($location)) {
    $sql .= " AND j.location LIKE '%$location%'";
}

$sql .= " ORDER BY j.created_at DESC";

$result = mysqli_query($conn, $sql);

// Output
if (mysqli_num_rows($result) > 0) {
    echo '<div class="space-y-4">';
    while ($row = mysqli_fetch_assoc($result)) {
        $is_saved = in_array($row['job_id'], $saved_job);
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center">
                    <img src="../uploads/company_logo/<?= htmlspecialchars($row['logo']) ?>"
                        alt="<?= htmlspecialchars($row['name']) ?>" 
                        class="w-12 h-12 rounded-lg mr-4 object-cover">
                    <div>
                        <h3 class="text-lg font-semibold"><?= htmlspecialchars($row['title']) ?></h3>
                        <p class="text-gray-600"><?= htmlspecialchars($row['name']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($row['location']) ?></p>
                    </div>
                </div>
                <button class="save-job-btn transition <?= $is_saved ? 'text-red-500' : 'text-gray-400 hover:text-red-500' ?>"
                    title="Save Job" data-job-id="<?= htmlspecialchars($row['job_id']) ?>">
                    <i class="<?= $is_saved ? 'fas' : 'far' ?> fa-heart text-xl"></i>
                </button>
            </div>

            <p class="text-gray-700 mb-4"><?= htmlspecialchars($row['description_title']) ?></p>

            <div class="flex flex-wrap gap-2 mb-4">
                <?php foreach (explode(',', $row['skills_required']) as $skill): ?>
                    <span class="bg-green-100 text-black px-3 py-1 rounded-full text-sm">
                        <?= htmlspecialchars(trim($skill)) ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-green-600 font-semibold">
                    ₹<?= intval($row['salary_min']) ?> - ₹<?= intval($row['salary_max']) ?>
                </span>
                <a href="job_details.php?job_id=<?= $row['job_id'] ?>"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    Apply Now
                </a>
            </div>
        </div>
        <?php
    }
    echo '</div>';
} else {
    echo '<div class="p-4 bg-white rounded-lg shadow-md text-gray-500">No jobs found matching your search.</div>';
}
?>
