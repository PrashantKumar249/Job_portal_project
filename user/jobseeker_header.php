<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['jobseeker_id'])) return;

$jobseeker_name = $_SESSION['jobseeker_name'] ?? 'Your Name';
$jobseeker_email = $_SESSION['jobseeker_email'] ?? 'you@example.com';
$jobseeker_phone = $_SESSION['jobseeker_phone'] ?? '+91 XXXXXXXXXX';
?>

<!-- Tailwind Fixed Header -->
<header class="bg-white shadow-md fixed top-0 w-full z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      
      <!-- Logo -->
      <a href="dashboard.php" class="text-blue-600 font-bold text-2xl tracking-tight">
        naukri<span class="text-gray-800">Pro</span>
      </a>

      <!-- Profile Toggle -->
      <div class="relative">
        <button onclick="toggleProfileSection()" class="text-gray-800 font-medium text-sm hover:underline focus:outline-none">
          Profile
        </button>

        <!-- Profile Section -->
        <div id="profileSection" class="hidden absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
          <div class="p-4 space-y-2">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Your Details</h3>
            <div class="text-sm text-gray-600">
              <p><strong>Name:</strong> <?= htmlspecialchars($jobseeker_name) ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($jobseeker_email) ?></p>
              <p><strong>Phone:</strong> <?= htmlspecialchars($jobseeker_phone) ?></p>
            </div>
            <a href="edit_profile.php" class="block text-center mt-4 bg-blue-600 text-white py-1.5 rounded-md text-sm hover:bg-blue-700 transition">
              Edit Profile
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</header>

<!-- JS for toggle -->
<script>
function toggleProfileSection() {
  const box = document.getElementById('profileSection');
  box.classList.toggle('hidden');

  // Hide on outside click
  document.addEventListener('click', function handler(e) {
    if (!box.contains(e.target) && !e.target.closest('button')) {
      box.classList.add('hidden');
      document.removeEventListener('click', handler);
    }
  });
}
</script>
