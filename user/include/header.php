<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NaukariPro - Find Your Dream Job</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-50">
  <!-- Navbar -->
  <nav class="bg-white shadow-lg sticky top-0 z-50" x-data="{ menuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <h1 class="text-2xl font-bold text-blue-600">NaukariPro</h1>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:block">
          <div class="flex items-baseline space-x-4">
            <a href="dashboard.php" class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
            <a href="view_all_jobs.php" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Jobs</a>
            <a href="companies.php" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Companies</a>
            <a href="about.php" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
          </div>
        </div>

        <!-- Right section -->
        <div class="flex items-center space-x-4">
          <!-- Notification -->
          <button class="text-gray-500 hover:text-blue-600 hidden sm:block">
            <i class="fas fa-bell text-xl"></i>
          </button>

          <!-- Profile Dropdown -->
          <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="text-gray-600 hover:text-blue-600 focus:outline-none">
              <i class="fas fa-user-circle text-2xl"></i>
            </button>
            <div x-show="open" @click.away="open = false" x-transition
              class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50">
              <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-user mr-2"></i> Profile
              </a>
              <a href="applied_jobs.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-briefcase mr-2"></i> Applied Jobs
              </a>
              <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
              </a>
            </div>
          </div>

          <!-- Mobile menu button -->
          <button class="md:hidden text-gray-500 hover:text-blue-600 focus:outline-none"
            @click="menuOpen = !menuOpen">
            <i class="fas fa-bars text-xl"></i>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div x-show="menuOpen" class="md:hidden pb-4 space-y-2">
        <a href="dashboard.php" class="block px-3 py-2 text-gray-900 hover:text-blue-600">Home</a>
        <a href="view_all_jobs.php" class="block px-3 py-2 text-gray-500 hover:text-blue-600">Jobs</a>
        <a href="companies.php" class="block px-3 py-2 text-gray-500 hover:text-blue-600">Companies</a>
        <a href="about.php" class="block px-3 py-2 text-gray-500 hover:text-blue-600">About</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl sm:text-4xl font-bold mb-4">Find Your Dream Job Today</h2>
      <p class="text-lg sm:text-xl mb-8">Discover thousands of job opportunities from top companies</p>

      <!-- Search Bar -->
      <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-4">
        <div class="flex flex-col sm:flex-row gap-2">
          <div class="flex-1">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
              <input id="search_job" type="text" placeholder="Job title, keyword or company"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 h-10">
              <div id="suggestion-box"
                class="absolute bg-white border border-gray-200 rounded-lg mt-1 w-full hidden z-10 shadow-md max-h-60 overflow-y-auto"></div>
            </div>
          </div>

          <button id="search_btn"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 h-10 rounded-lg font-semibold transition duration-200">
            Search
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Search Results Container -->
  <div id="jobResults" class="max-w-5xl mx-auto px-4 mt-6 sm:mt-8"></div>

  <script>
    $(document).ready(function () {
      // Suggestions
      $('#search_job').on('keyup input', function () {
        let query = $(this).val();
        if (query.length > 0) {
          $.post('search_suggest.php', { query: query }, function (data) {
            $('#suggestion-box').html(data).removeClass('hidden');
          });
        } else {
          $('#suggestion-box').addClass('hidden');
        }
      });

      // Click on suggestion
      $(document).on('click', '.suggestion-item', function () {
        let text = $(this).text();
        $('#search_job').val(text);
        $('#suggestion-box').addClass('hidden');
        searchJobs(text);
      });

      // Search button
      $('#search_btn').click(function () {
        let job = $('#search_job').val().trim();
        if (job === "") {
          alert("Please enter a job title or keyword");
          return;
        }
        searchJobs(job);
      });

      // Enter key
      $('#search_job').keypress(function (e) {
        if (e.which == 13) {
          e.preventDefault();
          searchJobs($('#search_job').val().trim());
        }
      });

      function searchJobs(job) {
        $.post('search_job.php', { job: job }, function (data) {
          $('#jobResults').html(data);
        });
      }
    });
  </script>
</body>
</html>
