<?php
session_start();
if (!isset($_SESSION['jobseeker_id'])) {
  header("Location: login.php");
  exit();
}
$jobseeker_id = $_SESSION["jobseeker_id"];

require_once '../inc/db.php';

// Fetch top 3 companies with most job posts
$sql_top_companies = "
    SELECT c.name, c.logo, COUNT(j.job_id) AS total_jobs
    FROM jobs j
    INNER JOIN companies c ON j.company_id = c.company_id
    GROUP BY c.company_id
    ORDER BY total_jobs DESC
    LIMIT 5";
$result_top_companies = mysqli_query($conn, $sql_top_companies);

// Define job categories with keywords
$categories = [
  'Technology' => ['%tech%', '%software%', '%developer%', '%engineering%'],
  'Marketing' => ['%marketing%', '%content%', '%seo%', '%growth%'],
  'Design' => ['%design%', '%ui%', '%ux%', '%graphic%'],
  'Sales' => ['%sales%', '%account%', '%business%', '%growth%'],
  'Human Resources' => ['%hr%', '%recruitment%', '%talent%', '%employee%'],
];


// Fetch user's role securely
// Get user's role(s)
$user_role_explode = [];
$user_role_stmt = $conn->prepare("SELECT role FROM jobseekers WHERE jobseeker_id = ?");
$user_role_stmt->bind_param("s", $jobseeker_id);
$user_role_stmt->execute();
$user_role_stmt->bind_result($user_role);
if ($user_role_stmt->fetch()) {
  $user_role_explode = explode(' ', $user_role);
}
$user_role_stmt->close();

// Build dynamic WHERE conditions for each role word
$conditions = [];
$params = [];
$types = '';

foreach ($user_role_explode as $role_word) {
  $conditions[] = "j.title LIKE ?";
  $params[] = "%" . $role_word . "%";
  $types .= 's';
}

// If no roles found, match everything
if (empty($conditions)) {
  $conditions[] = "1"; // Always true
}

// Final SQL
$sql = "
    SELECT j.*, c.name AS company_name, c.logo AS company_logo
    FROM jobs j
    INNER JOIN companies c ON j.company_id = c.company_id
    WHERE " . implode(" OR ", $conditions) . "
    ORDER BY j.job_id DESC
    LIMIT 10
";

$recommended_stmt = $conn->prepare($sql);

// Bind only if we have role words
if (!empty($params)) {
  $recommended_stmt->bind_param($types, ...$params);
}

$recommended_stmt->execute();
$recommendedResult = $recommended_stmt->get_result();


// Get job counts for each category
$categoryCounts = [];
foreach ($categories as $categoryName => $patterns) {
  $where = implode(' OR ', array_fill(0, count($patterns), 'title LIKE ?'));
  $query = "SELECT COUNT(*) as total FROM jobs WHERE $where";
  $stmt = mysqli_prepare($conn, $query);
  $types = str_repeat('s', count($patterns));
  mysqli_stmt_bind_param($stmt, $types, ...$patterns);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $countRow = mysqli_fetch_assoc($result);
  $categoryCounts[$categoryName] = $countRow['total'] ?? 0;
}

$jobseeker_id = $_SESSION['jobseeker_id'];
$saved_query = "SELECT job_id FROM saved_jobs WHERE jobseeker_id= '$jobseeker_id'";
$saved_result = mysqli_query($conn, $saved_query);
$saved_job = [];
while ($row = mysqli_fetch_assoc($saved_result)) {
  $saved_job[] = $row['job_id'];
}



include('include/header.php');
?>



<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="flex flex-col lg:flex-row gap-8">
    <!-- Sidebar Filters -->
    <aside class="lg:w-1/4">
      <form id="filterForm" class="bg-white rounded-lg shadow-md p-6 sticky top-24">
        <h3 class="text-lg font-semibold mb-4">Filter Jobs</h3>

        <!-- Job Type -->
        <div class="mb-6">
          <h4 class="font-medium mb-3">Job Type</h4>
          <div class="space-y-2">
            <label class="flex items-center">
              <input type="checkbox" name="job_type[]" value="internship"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Internship</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" name="job_type[]" value="part-time"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Part-time</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" name="job_type[]" value="full-time"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Full-time</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" name="job_type[]" value="contract"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Contract</span>
            </label>
          </div>
        </div>

        <!-- Experience Level -->
        <div class="mb-6">
          <h4 class="font-medium mb-3">Experience Level</h4>
          <div class="space-y-2">
            <label class="flex items-center">
              <input type="checkbox" name="experience_level[]" value="fresher"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Fresher</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" name="experience_level[]" value="junior"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Junior</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" name="experience_level[]" value="mid"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Mid</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" name="experience_level[]" value="senior"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Senior</span>
            </label>
          </div>
        </div>

        <!-- Salary Range -->
        <div class="mb-6">
          <h4 class="font-medium mb-3">Salary Range</h4>
          <select id="salary_range" name="salary_range"
            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Any Salary</option>
            <option value="30000-50000">$30k - $50k</option>
            <option value="50000-75000">$50k - $75k</option>
            <option value="75000-100000">$75k - $100k</option>
            <option value="100000-9999999">$100k+</option>
          </select>

        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition duration-200">
          Apply Filters
        </button>
      </form>
    </aside>



    <!-- Main Content -->
    <main class="lg:w-3/4">
      <!-- Top Companies Hiring -->
      <section class="mb-8">
        <h2 class="text-2xl font-bold mb-6">Top Companies Hiring</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <?php while ($row_top_companies = mysqli_fetch_assoc($result_top_companies)): ?>
            <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
              <img src="../uploads/company_logo/<?= htmlspecialchars($row_top_companies['logo']) ?>"
                alt="<?= htmlspecialchars($row_top_companies['name']) ?>" class="w-12 h-12 mx-auto mb-2 object-contain">
              <h3 class="font-semibold"><?= htmlspecialchars($row_top_companies['name']) ?></h3>
              <p class="text-sm text-gray-600"><?= $row_top_companies['total_jobs'] ?> open positions</p>
            </div>
          <?php endwhile; ?>
        </div>
      </section>

      <div id="job-results"></div>

      <!-- Recommended Jobs -->
      <section class="mb-8">
        <h2 class="text-2xl font-bold mb-6">Recommended for You</h2>
        <div class="space-y-4">
          <?php while ($row = mysqli_fetch_assoc($recommendedResult)): ?>
            <?php
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

                <button
                  class="save-job-btn transition <?= $is_saved ? 'text-red-500' : 'text-gray-400 hover:text-red-500' ?>"
                  title="Save Job" data-job-id="<?= htmlspecialchars($row['job_id']) ?>">
                  <i class="<?= $is_saved ? 'fas' : 'far' ?> fa-heart text-xl"></i>
                </button>

                <!-- Heart Button -->
                <!-- <button class="save-job-btn transition-colors duration-200 <?= $is_saved ? 'text-red-600' : 'text-gray-400 hover:text-orange-500'; ?> title="Save Job"
                  data-job-id="<?= $row['job_id'] ?>">
                  <i class="far fa-heart text-xl"></i>
                </button>  -->

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
          <?php endwhile; ?>
        </div>
      </section>

      <!-- Explore by Category -->
      <section class="mb-8">
        <h2 class="text-2xl font-bold mb-6">Explore Jobs by Category</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <?php foreach ($categoryCounts as $categoryName => $count): ?>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 cursor-pointer">
              <div class="text-blue-600 text-3xl mb-4">
                <i class="fas fa-code"></i>
              </div>
              <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($categoryName) ?></h3>
              <p class="text-gray-600 mb-4">Roles in <?= htmlspecialchars($categoryName) ?> domain</p>
              <p class="text-blue-600 font-semibold"><?= $count ?> jobs available</p>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- Other Jobs Section -->
      <section class="mb-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold">Other Job Opportunities</h2>
          <a href="view_all_jobs.php" class="text-blue-600 hover:text-blue-700 font-semibold">View All</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <?php
          $otherJobsQuery = "SELECT jobs.*, companies.name AS company_name FROM jobs JOIN companies ON jobs.company_id = companies.company_id ORDER BY job_id DESC LIMIT 8";
          $otherJobsResult = mysqli_query($conn, $otherJobsQuery);
          while ($row = mysqli_fetch_assoc($otherJobsResult)):
            $skills = explode(',', $row['skills_required']);
            ?>
            <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition duration-200">
              <div class="flex justify-between items-start mb-3">
                <div>
                  <h3 class="font-semibold"><?= htmlspecialchars($row['title']) ?></h3>
                  <p class="text-gray-600 text-sm"><?= htmlspecialchars($row['company_name']) ?></p>
                  <p class="text-gray-500 text-xs"><?= htmlspecialchars($row['location']) ?></p>
                </div>
                <span class="text-green-600 font-semibold text-sm">
                  ₹<?= intval($row['salary_min']) ?> - ₹<?= intval($row['salary_max']) ?>
                </span>
              </div>
              <div class="flex flex-wrap gap-1 mb-3">
                <?php foreach ($skills as $skill): ?>
                  <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                    <?= htmlspecialchars(trim($skill)) ?>
                  </span>
                <?php endforeach; ?>
              </div>
              <a href="job_details.php?job_id=<?= $row['job_id'] ?>"
                class="w-full block bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-2 rounded font-semibold transition duration-200">
                View Details
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      </section>

    </main>
  </div>
</div>

<?php include 'include/footer.php'; ?>

<script>
  function loadJobs() {
    var jobType = [];
    $('input[name="job_type[]"]:checked').each(function () {
      jobType.push($(this).val());
    });

    var experienceLevel = [];
    $('input[name="experience_level[]"]:checked').each(function () {
      experienceLevel.push($(this).val());
    });

    var salaryRange = $('#salary_range').val();
    console.log(salaryRange);

    $.ajax({
      url: "filter_job.php",
      type: "POST",
      data: {
        job_type: jobType,
        experience_level: experienceLevel,
        salary_range: salaryRange
      },
      success: function (data) {
        $('#job-results').html(data);
      }
    });
  }

  // Load jobs on page load
  $(document).ready(function () {
    loadJobs();

    // Reload when filters change
    $('input[name="job_type[]"], input[name="experience_level[]"], #salary_range').on('change', function () {
      loadJobs();
    });
  });
</script>