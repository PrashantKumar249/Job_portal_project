<?php
ob_start();
session_start();
include '../inc/db.php';

if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: register1.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = trim($_POST['role'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $resume_path = '';
    $jobseeker_id = $_SESSION['jobseeker_id'];

    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $file = $_FILES['resume'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $resume_path = '../uploads/resumes/' . $jobseeker_id . '_' . time() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $resume_path);
    }

    if ($role && $skills && $resume_path) {
        $query = "UPDATE jobseekers SET role = ?, skills = ?, bio = ?, resume = ? WHERE jobseeker_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $role, $skills, $bio, $resume_path, $jobseeker_id);
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Step 3: Final Details | Job Portal</title>
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
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/resume-upload-5388508-4497260.png"
             alt="Resume Illustration" class="w-36 mx-auto mb-6" />
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Finish Setting Up</h2>
        <ul class="text-sm text-gray-700 space-y-3">
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Let companies find you by role</li>
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Showcase your skills</li>
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Upload resume to get shortlisted faster</li>
        </ul>
      </div>

      <!-- Right Form -->
      <div class="p-8 md:p-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Final Details</h2>
        <p class="text-sm text-gray-600 mb-6">Tell us what role you're aiming for</p>

        <?php if (!empty($error)): ?>
          <div class="text-red-600 text-sm mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-5">

          <!-- Role -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Preferred Role</label>
            <input type="text" name="role" required
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="e.g. Frontend Developer, HR Manager" />
          </div>

        <!-- Skills -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Skills</label>
            <input type="text" name="skills" required
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                     placeholder="e.g. HTML, CSS, JavaScript, Python" />
          </div>

          <!-- bio -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Bio</label>
            <input type="text" name="bio" required
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                     placeholder="Enter your bio" />
          </div>


          <!-- Resume Upload -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Upload Resume</label>
            <input type="file" name="resume" accept=".pdf,.doc,.docx" required
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" />
          </div>

          <button type="submit"
                  class="w-full bg-blue-600 text-white py-2 rounded-md font-medium hover:bg-blue-700 transition">
            Finish Registration
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
