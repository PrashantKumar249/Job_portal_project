<?php
session_start();
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../inc/db.php';

// Define job categories with keywords
$categories = [
  'Technology' => ['%tech%', '%software%', '%developer%', '%engineering%'],
  'Marketing' => ['%marketing%', '%content%', '%seo%', '%growth%'],
  'Design' => ['%design%', '%ui%', '%ux%', '%graphic%'],
  'Sales' => ['%sales%', '%account%', '%business%', '%growth%'],
  'Human Resources' => ['%hr%', '%recruitment%', '%talent%', '%employee%'],
];

// Fetch recommended jobs (with company names)
$recommendedQuery = "
  SELECT jobs.*, companies.name AS company_name, companies.logo AS company_logo
  FROM jobs 
  INNER JOIN companies ON jobs.company_id = companies.company_id 
  ORDER BY job_id DESC 
  LIMIT 10";
$recommendedResult = mysqli_query($conn, $recommendedQuery);


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

include('include/header.php');
?>



<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="flex flex-col lg:flex-row gap-8">
    <!-- Sidebar Filters -->
    <aside class="lg:w-1/4">
      <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
        <h3 class="text-lg font-semibold mb-4">Filter Jobs</h3>

        <!-- Job Type Filter -->
        <div class="mb-6">
          <h4 class="font-medium mb-3">Job Type</h4>
          <div class="space-y-2">
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Internship</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Part-time</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Full-time</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Contract</span>
            </label>
          </div>
        </div>

        <!-- Experience Level -->
        <div class="mb-6">
          <h4 class="font-medium mb-3">Experience Level</h4>
          <div class="space-y-2">
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Fresher</span>
            </label>
             <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Junior</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Mid</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm">Senior</span>
            </label>
          </div>
        </div>

        <!-- Salary Range -->
        <div class="mb-6">
          <h4 class="font-medium mb-3">Salary Range</h4>
          <select class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option>Any Salary</option>
            <option>$30k - $50k</option>
            <option>$50k - $75k</option>
            <option>$75k - $100k</option>
            <option>$100k+</option>
          </select>
        </div>

        <button
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition duration-200">
          Apply Filters
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:w-3/4">
      <!-- Top Companies Hiring -->
      <section class="mb-8">
        <h2 class="text-2xl font-bold mb-6">Top Companies Hiring</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
            <img src="<? $row['logo'] ?>?height=60&width=60" alt="Google" class="w-12 h-12 mx-auto mb-2">
            <h3 class="font-semibold">Google</h3>
            <p class="text-sm text-gray-600">45 open positions</p>
          </div>
          <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
            <img src="<? $row['logo'] ?>?height=60&width=60" alt="Microsoft" class="w-12 h-12 mx-auto mb-2">
            <h3 class="font-semibold">Microsoft</h3>
            <p class="text-sm text-gray-600">32 open positions</p>
          </div>
          <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
            <img src="<? $row['logo'] ?>?height=60&width=60" alt="Apple" class="w-12 h-12 mx-auto mb-2">
            <h3 class="font-semibold">Apple</h3>
            <p class="text-sm text-gray-600">28 open positions</p>
          </div>
          <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200">
            <img src="<? $row['logo'] ?>?height=60&width=60" alt="Amazon" class="w-12 h-12 mx-auto mb-2">
            <h3 class="font-semibold">Amazon</h3>
            <p class="text-sm text-gray-600">67 open positions</p>
          </div>
        </div>
      </section>

      <!-- Recommended Jobs -->
      <section class="mb-8">
        <h2 class="text-2xl font-bold mb-6">Recommended for You</h2>
        <div class="space-y-4">
          <?php while ($row = mysqli_fetch_assoc($recommendedResult)): ?>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
              <div class="flex justify-between items-start mb-4">
                <div class="flex items-center">
                  <img src="../uploads/company_logos/<?= htmlspecialchars($row['company_logo']) ?>?height=50&width=50"
                    alt="<?= htmlspecialchars($row['company_name']) ?>" class="w-12 h-12 rounded-lg mr-4">
                  <div>
                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($row['title']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($row['company_name']) ?></p>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($row['location']) ?></p>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-red-500">
                  <i class="far fa-heart text-xl"></i>
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
  document.addEventListener('DOMContentLoaded', function () {
    const heartButtons = document.querySelectorAll('.fa-heart');
    heartButtons.forEach(button => {
      button.addEventListener('click', function () {
        this.classList.toggle('far');
        this.classList.toggle('fas');
        this.classList.toggle('text-red-500');
      });
    });
  });
</script>