<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

$adminId = $_SESSION['company_admin_id'];

// Fetch admin & company name
$sql = "SELECT ca.name AS admin_name, c.name AS company_name, c.company_id as company_id
        FROM company_admins ca 
        JOIN companies c ON ca.company_id = c.company_id 
        WHERE ca.company_admin_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminId);
$stmt->execute();
$result = $stmt->get_result();

$data = $result->fetch_assoc();
if (!$data) {
    echo "<p class='text-red-500'>Admin not found.</p>";
    exit();
}

$company_id = $data["company_id"];
/*echo $company_id;
$logo_fetch = "SELECT logo FROM companies WHERE company_id = ?";
$stmt = $conn->prepare($logo_fetch);
$stmt->bind_param("s", $data['company_id']);
$stmt->execute();
$logo_result = $stmt->get_result();
$logo_data = $logo_result->fetch_assoc();


echo "<img src='uploads/companies/" . htmlspecialchars($logo_data['logo']) . "' alt='' class='w-20 h-20 rounded-full mb-4 sm:mb-0 sm:mr-6 border object-cover'>";
*/
?>

<!-- DASHBOARD BODY -->
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-gray-800">
            👋 Welcome, <span class="text-blue-600"><?= htmlspecialchars($data['admin_name']) ?></span>
        </h1>
        <p class="text-lg text-gray-600 mt-1">
            Company: <span class="font-medium text-green-700"><?= htmlspecialchars($data['company_name']) ?></span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Post Job -->
        <a href="post_job.php"
            class="bg-white border border-gray-200 hover:border-blue-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition group">
            <div class="text-blue-700 text-3xl mb-3">➕</div>
            <h2 class="text-xl font-semibold group-hover:underline">Post a New Job</h2>
            <p class="text-gray-600 text-sm mt-1">Create and publish job openings.</p>
        </a>

        <!-- Manage Jobs -->
        <a href="manage_jobs.php"
            class="bg-white border border-gray-200 hover:border-blue-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition group">
            <div class="text-blue-700 text-3xl mb-3">🗂️</div>
            <h2 class="text-xl font-semibold group-hover:underline">Manage Job Listings</h2>
            <p class="text-gray-600 text-sm mt-1">Edit or remove job posts.</p>
        </a>

        <!-- View Applicants -->
        <a href="applicants.php"
            class="bg-white border border-gray-200 hover:border-blue-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition group">
            <div class="text-blue-700 text-3xl mb-3">👥</div>
            <h2 class="text-xl font-semibold group-hover:underline">View Applicants</h2>
            <p class="text-gray-600 text-sm mt-1">Check job applications and change status.</p>
        </a>

        <!-- Schedule Interview -->
        <a href="schedule_interview.php"
            class="bg-white border border-gray-200 hover:border-blue-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition group">
            <div class="text-blue-700 text-3xl mb-3">🗓️</div>
            <h2 class="text-xl font-semibold group-hover:underline">Schedule Interviews</h2>
            <p class="text-gray-600 text-sm mt-1">Plan interviews with selected candidates.</p>
        </a>

        <!-- Job Seeker Profiles -->
        <a href="job_seekers.php"
            class="bg-white border border-gray-200 hover:border-blue-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition group">
            <div class="text-blue-700 text-3xl mb-3">📄</div>
            <h2 class="text-xl font-semibold group-hover:underline">Job Seeker Profiles</h2>
            <p class="text-gray-600 text-sm mt-1">View seeker profiles and resume data.</p>
        </a>

    </div>
</div>
