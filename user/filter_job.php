<?php
include '../inc/db.php';
session_start();

$saved_job = [];
if (isset($_SESSION['jobseeker_id'])) {
    $jobseeker_id = $_SESSION['jobseeker_id'];
    $savedQuery = mysqli_query($conn, "SELECT job_id FROM saved_jobs WHERE jobseeker_id = '$jobseeker_id'");
    while ($savedRow = mysqli_fetch_assoc($savedQuery)) {
        $saved_job[] = $savedRow['job_id'];
    }
}

// Collect filter values
$jobType = isset($_POST['job_type']) ? $_POST['job_type'] : [];
$experienceLevel = isset($_POST['experience_level']) ? $_POST['experience_level'] : [];
$salaryRange = isset($_POST['salary_range']) ? $_POST['salary_range'] : '';
// echo $salaryRange;
// die();
// Check if at least one filter is set
if (
    !empty($_POST['job_type']) ||
    !empty($_POST['experience_level']) ||
    !empty($_POST['salary_range'])
) {

    $sql = "SELECT jobs.*, companies.name AS company_name, companies.logo AS company_logo 
        FROM jobs 
        INNER JOIN companies ON jobs.company_id = companies.company_id 
        WHERE 1=1";

    // Apply filters
    if (!empty($jobType)) {
        $types = "'" . implode("','", array_map(function ($type) use ($conn) {
            return mysqli_real_escape_string($conn, $type);
        }, $jobType)) . "'";
        $sql .= " AND jobs.employment_type IN ($types)";
    }


    if (!empty($experienceLevel)) {
        $levels = "'" . implode("','", array_map(function ($level) use ($conn) {
            return mysqli_real_escape_string($conn, $level);
        }, $experienceLevel)) . "'";
        $sql .= " AND jobs.experience_level IN ($levels)";
    }

    // Salary Range Filter
    if (!empty($_POST['salary_range'])) {
        $range = explode('-', $_POST['salary_range']);
        $minSalary = (int) $range[0];
        $maxSalary = (int) $range[1];

        // Show jobs that overlap with selected range
        $sql .= " AND jobs.salary_max >= $minSalary 
              AND jobs.salary_min <= $maxSalary";
    }


    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="space-y-4">';
        while ($row = mysqli_fetch_assoc($result)) {
            $is_saved = in_array($row['job_id'], $saved_job);
            ?>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <img src="../uploads/company_logo/<?= htmlspecialchars($row['company_logo']) ?>?height=50&width=50"
                            alt="<?= htmlspecialchars($row['company_name']) ?>" class="w-12 h-12 rounded-lg mr-4">
                        <div>
                            <h3 class="text-lg font-semibold"><?= htmlspecialchars($row['title']) ?></h3>
                            <p class="text-gray-600"><?= htmlspecialchars($row['company_name']) ?></p>
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
        echo '<div class="p-4 bg-white rounded-lg shadow-md text-gray-500">No jobs found matching your filters.</div>';
    }
}
?>