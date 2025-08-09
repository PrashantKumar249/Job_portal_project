<?php

include '../inc/db.php';
include('include/header.php');

$limit = 4;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$countQuery = "SELECT COUNT(*) AS total FROM jobs";
$countResult = mysqli_query($conn, $countQuery);
$rowCount = mysqli_fetch_assoc($countResult);
$totalJobs = $rowCount['total'];
$totalPages = ceil($totalJobs / $limit);
?>

<div class="max-w-6xl mx-auto px-4 py-8 bg-gray-50">
  <h1 class="text-3xl font-bold mb-6">All Job Opportunities</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php
    $jobsQuery = "SELECT jobs.*, companies.name AS company_name FROM jobs 
                  JOIN companies ON jobs.company_id = companies.company_id 
                  ORDER BY job_id DESC LIMIT $limit OFFSET $offset";
    $jobsResult = mysqli_query($conn, $jobsQuery);

    if (mysqli_num_rows($jobsResult) > 0):
      while ($row = mysqli_fetch_assoc($jobsResult)):
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
    <?php else: ?>
      <p class="text-gray-500">No jobs available at the moment.</p>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <div class="mt-8 flex justify-center items-center space-x-2">
    <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">Prev</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?= $i ?>" class="px-3 py-1 <?= $i == $page ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' ?> rounded">
        <?= $i ?>
      </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
      <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">Next</a>
    <?php endif; ?>
  </div>
</div>
<?php include('include/footer.php'); ?>