<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

$adminName = $_SESSION['company_admin_name'] ?? 'Company Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header -->
<header class="bg-gradient-to-r from-green-700 to-emerald-700 text-white shadow-md">
  <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col sm:flex-row justify-between items-center">
    
    <!-- Left: Title -->
    <div class="text-center sm:text-left">
      <h1 class="text-3xl font-extrabold tracking-wide">Company Admin Panel</h1>
      <p class="text-sm text-green-200 mt-1">Manage your jobs & applications</p>
    </div>

    <!-- Right: Dropdown -->
    <div class="mt-4 sm:mt-0 relative">
      <button id="dropdownBtn" class="flex items-center gap-2 bg-green-600 px-3 py-1.5 rounded-full shadow hover:bg-green-500 focus:outline-none">
        👋 Hello, <strong><?= htmlspecialchars($adminName); ?></strong>
        <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <!-- Dropdown Menu (controlled by JS) -->
      <div id="dropdownMenu" class="absolute right-0 mt-2 w-56 bg-white text-gray-800 rounded shadow-lg z-50 hidden">
        <a href="dashboard.php" class="block px-4 py-2 hover:bg-gray-100">🏠 Home</a>
        <a href="manage_jobs.php" class="block px-4 py-2 hover:bg-gray-100">💼 Jobs</a>
        <a href="manage_jobs.php" class="block px-4 py-2 hover:bg-gray-100">📄 Applications</a>
        <hr class="my-1 border-t">
        <a href="admin_profile.php" class="block px-4 py-2 hover:bg-gray-100">👤 Profile</a>
        <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">🚪 Logout</a>
      </div>
    </div>
  </div>
</header>

<!-- JS for dropdown toggle -->
<script>
  const dropdownBtn = document.getElementById('dropdownBtn');
  const dropdownMenu = document.getElementById('dropdownMenu');

  // Toggle dropdown
  dropdownBtn.addEventListener('click', function (e) {
    e.stopPropagation(); // prevent event bubbling
    dropdownMenu.classList.toggle('hidden');
  });

  // Close dropdown when clicking outside
  window.addEventListener('click', function () {
    if (!dropdownMenu.classList.contains('hidden')) {
      dropdownMenu.classList.add('hidden');
    }
  });
</script>

</body>
</html>
