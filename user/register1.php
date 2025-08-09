<?php
session_start();
include '../inc/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $experience_level = $_POST['experience_level'];

    // Check if email already exists
    $check = $conn->prepare("SELECT jobseeker_id FROM jobseekers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "This email is already registered. Please use a different one or login.";
    } else {
        $jobseeker_id = 'JS' . rand(1000, 9999); // Safer 4-digit ID

        $query = "INSERT INTO jobseekers (jobseeker_id, name, email, phone, password, experience_level)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $jobseeker_id, $name, $email, $phone, $password, $experience_level);

        if ($stmt->execute()) {
            $_SESSION['jobseeker_id'] = $jobseeker_id;
            header("Location: register2.php");
            exit();
        } else {
            $error = "Something went wrong. Please try again later.";
        }
    }

    $check->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Jobseeker Profile | Job Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <!-- Top Login -->
  <div class="w-full flex justify-end items-center px-6 py-4 text-sm">
    <span>Already Registered?</span>
    <a href="login.php" class="text-blue-600 font-medium ml-2 hover:underline">Login here</a>
  </div>

  <div class="min-h-screen flex items-center justify-center px-4 py-6">
    <div class="bg-white shadow-md rounded-2xl w-full max-w-6xl grid grid-cols-1 md:grid-cols-3 md:gap-0">

      <!-- Left Side Compact -->
      <div class="col-span-1 bg-blue-50 p-6 md:p-8 flex flex-col justify-center items-start">
       <img src="../uploads/images/register_images.jpg" alt="illustration" class="w-24 mb-4" />

        <h2 class="text-lg font-semibold text-gray-800 mb-3">On registering, you can</h2>
        <ul class="text-sm text-gray-700 space-y-2">
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Build your profile and let recruiters find you</li>
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Get job postings delivered to your email</li>
          <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Find a job and grow your career</li>
        </ul>
      </div>

      <!-- Right Form Side (Wider) -->
      <div class="col-span-2 p-6 md:p-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Create your Jobseeker Profile</h2>
        <p class="text-sm text-gray-600 mb-6">Search & apply to jobs across top companies</p>

        <?php if (!empty($error)): ?>
          <div class="text-red-600 text-sm mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700">Full Name<span class="text-red-500">*</span></label>
            <input type="text" name="name" required placeholder="What is your name?"
                   class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Email ID<span class="text-red-500">*</span></label>
            <input type="email" name="email" required placeholder="Tell us your Email ID"
                   class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            <p class="text-xs text-gray-500 mt-1">We'll send relevant jobs and updates to this email</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Password<span class="text-red-500">*</span></label>
            <input type="password" name="password" required minlength="6" placeholder="Minimum 6 characters"
                   class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            <p class="text-xs text-gray-500 mt-1">This helps your account stay protected</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Mobile Number<span class="text-red-500">*</span></label>
            <input type="text" name="phone" required maxlength="20" placeholder="+91 Enter your mobile number"
                   class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            <p class="text-xs text-gray-500 mt-1">Recruiters will contact you on this number</p>
          </div>

          <!-- Experience Level Section -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Work Status<span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

              <?php
              $levels = [
                  "fresher" => ["Fresher", "No experience yet / student"],
                  "junior" => ["Junior", "0–2 years experience"],
                  "mid" => ["Mid-Level", "2–5 years experience"],
                  "senior" => ["Senior", "5+ years experience"]
              ];
              foreach ($levels as $value => [$title, $desc]): ?>
                <label class="border rounded-lg p-4 cursor-pointer flex items-start gap-3 hover:border-blue-500 transition">
                  <input type="radio" name="experience_level" value="<?= $value ?>" required class="mt-1 accent-blue-600" />
                  <div>
                    <p class="font-semibold text-gray-800"><?= $title ?></p>
                    <p class="text-sm text-gray-500"><?= $desc ?></p>
                  </div>
                </label>
              <?php endforeach; ?>

            </div>
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
