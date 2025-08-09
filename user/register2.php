<?php
ob_start();
session_start();
include '../inc/db.php';

if (!isset($_SESSION['jobseeker_id'])) {
  header("Location: register1.php");
  exit();
}

// ✅ Initialize variables to avoid undefined warnings
$education = '';
$course = '';
$specialization = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $education = trim($_POST['education'] ?? '');
  $course = trim($_POST['course'] ?? '');
  $specialization = trim($_POST['specialization'] ?? '');
  $jobseeker_id = $_SESSION['jobseeker_id'];

  if ($education && $course && $specialization) {
    $query = "UPDATE jobseekers SET education = ?, course = ?, specialization = ? WHERE jobseeker_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
      $stmt->bind_param("ssss", $education, $course, $specialization, $jobseeker_id);
      if ($stmt->execute()) {
        header("Location: register3.php");
        exit();
      } else {
        $error = "❌ Error saving data: " . $stmt->error;
      }
    } else {
      $error = "❌ Prepare failed: " . $conn->error;
    }
  } else {
    $error = " All fields are required.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Step 2: Education Details | Job Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

  <!-- Top Login -->
  <div class="w-full flex justify-end items-center px-6 py-4 text-sm">
    <a href="login.php" class="text-blue-600 font-medium hover:underline">Login</a>
  </div>

  <div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 overflow-hidden">

      <!-- Left Info -->
      <div class="bg-blue-50 p-8 flex flex-col justify-center">
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/education-3747274-3140403.png"
          alt="Education Illustration" class="w-36 mx-auto mb-6" />
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Why share your education?</h2>
        <ul class="text-sm text-gray-700 space-y-3">
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Get jobs that match your qualification</li>
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Stand out to recruiters</li>
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Improve job recommendations</li>
        </ul>
      </div>

      <!-- Right Form -->
      <div class="p-8 md:p-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Education Details</h2>
        <p class="text-sm text-gray-600 mb-6">Tell us about your highest qualification</p>

        <?php if (!empty($error)): ?>
          <div class="text-red-600 text-sm mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">

          <!-- Highest Qualification -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Highest Qualification</label>
            <select name="education" required
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Select</option>
              <option value="Doctorate/PHD" <?= ($education === 'Doctorate/PHD') ? 'selected' : '' ?>>Doctorate / PhD</option>
              <option value="Masters/Post Graduation" <?= ($education === 'Masters/Post Graduation') ? 'selected' : '' ?>>Masters / Post Graduation</option>
              <option value="Graduation/Diploma" <?= ($education === 'Graduation/Diploma') ? 'selected' : '' ?>>Graduation / Diploma</option>
              <option value="12th" <?= ($education === '12th') ? 'selected' : '' ?>>12th</option>
              <option value="10th" <?= ($education === '10th') ? 'selected' : '' ?>>10th</option>
            </select>
          </div>

          <!-- Course -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Course</label>
            <input type="text" name="course" value="<?= htmlspecialchars($course) ?>" required
              placeholder="e.g. B.Tech, MBA, M.Sc"
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <!-- Specialization -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Specialization</label>
            <input type="text" name="specialization" value="<?= htmlspecialchars($specialization) ?>" required
              placeholder="e.g. Computer Science, Marketing"
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-md font-medium hover:bg-blue-700 transition">
            Save & Continue
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
