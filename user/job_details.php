<?php
session_start();
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
} 

include '../inc/db.php';
include('include/header.php');

$job_id = $_GET['job_id'];

if (!$job_id) {
    echo "<div class='text-red-500 text-center p-4'>Job not found.</div>";
    exit;
}

$query = "SELECT jobs.*, companies.name AS company_name, companies.logo AS company_logo 
          FROM jobs 
           JOIN companies ON jobs.company_id = companies.company_id 
          WHERE job_id = '$job_id' LIMIT 1";
$result = mysqli_query($conn, $query);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    echo "<div class='text-red-500 text-center p-4'>Job not found.</div>";
    exit;
}

$skills = explode(',', $job['skills_required']);
?>

<div class="max-w-5xl mx-auto px-4 py-8">
  <div class="bg-white p-6 rounded-xl shadow-md md:p-10">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center mb-6">
      <img src="../uploads/company_logo/<?= htmlspecialchars($job['company_logo']) ?>" 
           alt="Company Logo" 
           class="w-20 h-20 rounded-full mb-4 sm:mb-0 sm:mr-6 border object-cover" />
      <div>
        <h1 class="text-xl sm:text-2xl font-bold"><?= htmlspecialchars($job['title']) ?></h1>
        <p class="text-gray-600"><?= htmlspecialchars($job['company_name']) ?></p>
        <p class="text-sm text-gray-500"><?= htmlspecialchars($job['location']) ?></p>
      </div>
    </div>

    <!-- Job Description -->
    <div class="mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-1">💼 Job Description</h2>
      <p class="text-gray-700"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
    </div>

    <!-- Skills -->
    <div class="mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-1">🛠️ Skills Required</h2>
      <div class="flex flex-wrap gap-2 mt-2">
        <?php foreach ($skills as $skill): ?>
          <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full"><?= htmlspecialchars(trim($skill)) ?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Responsibilities -->
    <?php if (!empty($job['responsibilities'])): ?>
      <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">📋 Responsibilities</h2>
        <p class="text-gray-700 whitespace-pre-line"><?= htmlspecialchars($job['responsibilities']) ?></p>
      </div>
    <?php endif; ?>

    <!-- Qualifications -->
    <?php if (!empty($job['qualifications'])): ?>
      <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">🎓 Qualifications</h2>
        <p class="text-gray-700 whitespace-pre-line"><?= htmlspecialchars($job['qualifications']) ?></p>
      </div>
    <?php endif; ?>

    <!-- Job Details Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
      <div>
        <p class="text-gray-600">💰 Salary Range</p>
        <p class="font-semibold">₹<?= $job['salary_min'] ?> - ₹<?= $job['salary_max'] ?></p>
      </div>
      <div>
        <p class="text-gray-600">🕒 Job Type</p>
        <p class="font-semibold"><?= htmlspecialchars($job['employment_type']) ?></p>
      </div>
      <div>
        <p class="text-gray-600">📅 Deadline</p>
        <p class="font-semibold"><?= date('d M Y', strtotime($job['deadline'])) ?></p>
      </div>
      <div>
        <p class="text-gray-600">⏳ Experience Required</p>
        <p class="font-semibold"><?= htmlspecialchars($job['experience_level']) ?></p>
      </div>
    </div>

    <!-- Apply Button -->
    <div class="mt-8 text-center">
      <!-- <a href="apply.php?job_id=<?= $job['job_id'] ?>"
         class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all">
      </a> -->
      <form action="apply.php" method="POST">
        <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all">Apply Now</button>
      </form>
    </div>

  </div>
</div>
<?php include('include/footer.php'); ?>