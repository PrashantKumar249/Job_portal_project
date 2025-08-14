<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

$company_id = $_SESSION['company_id'];
$posted_by = $_SESSION['company_admin_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = 'JOB' . rand(1000, 9999);

    $title = $_POST['title'];
    $description_title = $_POST['description_title'];
    $description = $_POST['description'];
    $skills_required = $_POST['skills_required'];
    $location = $_POST['location'];
    $salary_min = $_POST['salary_min'];
    $salary_max = $_POST['salary_max'];
    $employment_type = $_POST['employment_type'];
    $experience_level = $_POST['experience_level'];
    $deadline = $_POST['deadline'];

    $sql = "INSERT INTO jobs (job_id, company_id, posted_by, title, description_title, description, skills_required, location, salary_min, salary_max, employment_type, experience_level, deadline)
            VALUES ('$job_id', '$company_id', '$posted_by', '$title', '$description_title', '$description', '$skills_required', '$location', '$salary_min', '$salary_max', '$employment_type', '$experience_level', '$deadline')";

    if (mysqli_query($conn, $sql)) {
        $success = "✅ Job posted successfully!";
        header("Location: dashboard.php?success=" . urlencode($success));
    } else {
        $error = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md mt-10">
    <h2 class="text-3xl font-bold text-green-700 mb-6">📢 Post a New Job</h2>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 border border-green-400"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 border border-red-400"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div>
            <label class="block font-medium mb-1">Job Title</label>
            <input type="text" name="title" required
                class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500" />
        </div>

        <div>
            <label class="block font-medium mb-1">Description Title</label>
            <textarea name="description_title" rows="4" required
                class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500"></textarea>
        </div>

        <div>
            <label class="block font-medium mb-1">Description Detail</label>
            <textarea name="description" rows="4" required
                class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500"></textarea>
        </div>

        <div>
            <label class="block font-medium mb-1">Skills Required</label>
            <textarea name="skills_required" rows="2" placeholder="e.g. PHP, MySQL, Tailwind"
                class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Location</label>
                <input type="text" name="location"
                    class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500" />
            </div>

            <div>
                <label class="block font-medium mb-1">Deadline</label>
                <input type="date" name="deadline"
                    class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Min Salary</label>
                <input type="number" step="0.01" name="salary_min"
                    class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500" />
            </div>

            <div>
                <label class="block font-medium mb-1">Max Salary</label>
                <input type="number" step="0.01" name="salary_max"
                    class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Employment Type</label>
                <select name="employment_type" required
                    class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500">
                    <option value="">-- Select --</option>
                    <option value="full-time">Full-time</option>
                    <option value="part-time">Part-time</option>
                    <option value="internship">Internship</option>
                    <option value="contract">Contract</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Experience Level</label>
                <select name="experience_level" required
                    class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-green-500">
                    <option value="">-- Select --</option>
                    <option value="fresher">Fresher</option>
                    <option value="junior">Junior</option>
                    <option value="mid">Mid</option>
                    <option value="senior">Senior</option>
                </select>
            </div>
        </div>
        <div class="flex justify-between mt-6">
            <a href="dashboard.php"
                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded transition">
                🔙 Back to dashboard
            </a>
            <button type="submit"
                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded transition">
                ➕ Post Job
            </button>
        </div>

    </form>
</div>