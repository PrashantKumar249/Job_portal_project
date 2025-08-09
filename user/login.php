<?php
session_start();
include('../inc/db.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email && $password) {
        $query = "SELECT jobseeker_id, password FROM jobseekers WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($jobseeker_id, $db_password);
            $stmt->fetch();

            // For now, assuming plain text password (not recommended for production)
            if ($password === $db_password) {
                $_SESSION['jobseeker_id'] = $jobseeker_id;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with that email.";
        }

        $stmt->close();
    } else {
        $error = "Please enter both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | NaukriPro</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>

<body class="bg-gray-50">
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="bg-white shadow-xl rounded-xl w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 overflow-hidden">

      <!-- Left Info Panel -->
      <div class="bg-blue-50 p-8 flex flex-col justify-center">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Why Join NaukriPro?</h2>
        <ul class="text-sm text-gray-600 space-y-3">
          <li class="flex items-start gap-2"><span class="text-blue-600">✔</span> Build a stunning profile to stand out</li>
          <li class="flex items-start gap-2"><span class="text-blue-600">✔</span> Discover tailored job openings daily</li>
          <li class="flex items-start gap-2"><span class="text-blue-600">✔</span> Get noticed by top recruiters & companies</li>
          <li class="flex items-start gap-2"><span class="text-blue-600">✔</span> Stay updated on job application progress</li>
        </ul>
        <a href="register1.php"
           class="mt-6 inline-block text-center bg-white text-blue-600 font-medium border border-blue-600 px-6 py-2 rounded hover:bg-blue-600 hover:text-white transition">
           Create your Account</a>
      </div>

      <!-- Right Login Form -->
      <div class="p-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Login</h2>

        <?php if (!empty($error)): ?>
          <div class="bg-red-100 text-red-600 px-4 py-2 rounded mb-4 text-sm">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" required
              class="mt-1 w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" required
              class="mt-1 w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div class="flex items-center justify-between text-sm">
            <a href="#" class="text-blue-600 hover:underline">Forgot Password?</a>
          </div>

          <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-md font-medium hover:bg-blue-700 transition">Login</button>

          <div class="flex items-center justify-center text-gray-500 text-sm mt-4">
            <span class="border-t border-gray-300 flex-grow mr-2"></span>
            or
            <span class="border-t border-gray-300 flex-grow ml-2"></span>
          </div>

          <button type="button"
            class="w-full mt-4 flex items-center justify-center gap-2 border border-gray-300 rounded-md py-2 hover:bg-gray-100 transition">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google Icon" class="w-5 h-5">
            Sign in with Google
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
