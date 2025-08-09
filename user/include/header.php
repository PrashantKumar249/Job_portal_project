<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NaukariPro - Find Your Dream Job</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
  <!-- Navbar -->
  <nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <h1 class="text-2xl font-bold text-blue-600">NaukariPro</h1>
          </div>
          <div class="hidden md:block ml-10">
            <div class="flex items-baseline space-x-4">
              <a href="dashboard.php"
                class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
              <a href="view_all_jobs.php"
                class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Jobs</a>
              <a href="companies.php"
                class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Companies</a>
              <a href="about.php"
                class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
            </div>
          </div>
        </div>
        <!-- Add Alpine.js for dropdown functionality -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <div class="flex items-center space-x-4">
          <button class="text-gray-500 hover:text-blue-600">
            <i class="fas fa-bell text-xl"></i>
          </button>

          <!-- Dropdown starts here -->
          <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="text-gray-600 hover:text-blue-600 focus:outline-none">
              <i class="fas fa-user-circle text-2xl"></i>
            </button>

            <div x-show="open" @click.away="open = false" x-transition
              class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50">
              <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-user mr-2"></i> Profile
              </a>
              <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </nav>

  <!-- Hero Section with Search -->
  <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-4xl font-bold mb-4">Find Your Dream Job Today</h2>
      <p class="text-xl mb-8">Discover thousands of job opportunities from top companies</p>

      <!-- Search Bar -->
      <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-4">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="flex-1">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
              <input type="text" placeholder="Job title, keywords, or company"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
            </div>
          </div>
          <div class="flex-1">
            <div class="relative">
              <i class="fas fa-map-marker-alt absolute left-3 top-3 text-gray-400"></i>
              <input type="text" placeholder="City, state, or remote"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
            </div>
          </div>
          <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-semibold transition duration-200">
            Search Jobs
          </button>
        </div>
      </div>
    </div>
  </section>