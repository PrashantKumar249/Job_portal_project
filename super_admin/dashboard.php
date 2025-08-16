<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}

// Header Include
 include 'super_admin_header.php';
?>


<body class="bg-gray-50 min-h-screen font-sans">

  <!-- Dashboard Content -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center sm:text-left">Dashboard Features</h2>

    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
      <!-- Card 1 -->
      <a href="manage_companies.php" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow border-t-4 border-blue-500 p-6 group">
        <h3 class="text-lg font-semibold text-blue-700 group-hover:underline">Manage Companies</h3>
        <p class="text-gray-600 mt-2">View, add, and remove registered companies.</p>
      </a>

      <!-- Card 2 -->
      <a href="manage_admins.php" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow border-t-4 border-green-500 p-6 group">
        <h3 class="text-lg font-semibold text-green-700 group-hover:underline">Manage Company Admins</h3>
        <p class="text-gray-600 mt-2"> manage HR/company admin accounts.</p>
      </a>

      <!-- Card 3 -->
      <!-- <a href="view_logs.php" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow border-t-4 border-purple-500 p-6 group">
        <h3 class="text-lg font-semibold text-purple-700 group-hover:underline">Activity Logs</h3>
        <p class="text-gray-600 mt-2">View actions performed by superadmins and company admins.</p>
      </a> -->

      <!-- Card 4 -->
      <a href="jobseekers.php" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow border-t-4 border-purple-500 p-6 group">
        <h3 class="text-lg font-semibold text-purple-700 group-hover:underline">Manage Jobseekers</h3>
        <p class="text-gray-600 mt-2">View and remove  registered Jobseekers .</p>
      </a>
    </div>
  </main>

</body>

