<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

$job_id = $_GET['job_id'] ?? '';

if (!$job_id) {
    echo "<p class='text-red-500'>Invalid Job ID.</p>";
    exit();
}

// Fetch job details
$stmt = $conn->prepare("SELECT * FROM jobs WHERE job_id = ?");
$stmt->bind_param("s", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "<p class='text-red-500'>Job not found.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $skills_required = $_POST['skills_required'];
    $location = $_POST['location'];
    $salary_min = $_POST['salary_min'];
    $salary_max = $_POST['salary_max'];
    $employment_type = $_POST['employment_type'];
    $experience_level = $_POST['experience_level'];
    $deadline = $_POST['deadline'];

    $update_sql = "UPDATE jobs SET title=?, description=?, skills_required=?, location=?, salary_min=?, salary_max=?, employment_type=?, experience_level=?, deadline=? WHERE job_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssddssss", $title, $description, $skills_required, $location, $salary_min, $salary_max, $employment_type, $experience_level, $deadline, $job_id);
    $stmt->execute();

    header("Location: manage_jobs.php");
    exit();
}
?>

<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-blue-700 mb-6">✏️ Edit Job</h1>

    <form method="POST" class="space-y-6">
        <div>
            <label class="block font-medium mb-1">Job Title</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($job['title']) ?>"
                class="w-full border px-4 py-2 rounded" />
        </div>

        <div>
            <label class="block font-medium mb-1">Description</label>
            <textarea name="description" rows="4" required
                class="w-full border px-4 py-2 rounded"><?= htmlspecialchars($job['description']) ?></textarea>
        </div>

        <div>
            <label class="block font-medium mb-1">Skills Required</label>
            <textarea name="skills_required" rows="2"
                class="w-full border px-4 py-2 rounded"><?= htmlspecialchars($job['skills_required']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Location</label>
                <input type="text" name="location" value="<?= htmlspecialchars($job['location']) ?>"
                    class="w-full border px-4 py-2 rounded" />
            </div>
            <div>
                <label class="block font-medium mb-1">Deadline</label>
                <input type="date" name="deadline" value="<?= $job['deadline'] ?>"
                    class="w-full border px-4 py-2 rounded" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Min Salary</label>
                <input type="number" step="0.01" name="salary_min" value="<?= $job['salary_min'] ?>"
                    class="w-full border px-4 py-2 rounded" />
            </div>
            <div>
                <label class="block font-medium mb-1">Max Salary</label>
                <input type="number" step="0.01" name="salary_max" value="<?= $job['salary_max'] ?>"
                    class="w-full border px-4 py-2 rounded" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Employment Type</label>
                <select name="employment_type" required class="w-full border px-4 py-2 rounded">
                    <option value="">-- Select --</option>
                    <option value="full-time" <?= $job['employment_type'] === 'full-time' ? 'selected' : '' ?>>Full-time
                    </option>
                    <option value="part-time" <?= $job['employment_type'] === 'part-time' ? 'selected' : '' ?>>Part-time
                    </option>
                    <option value="internship" <?= $job['employment_type'] === 'internship' ? 'selected' : '' ?>>Internship
                    </option>
                    <option value="contract" <?= $job['employment_type'] === 'contract' ? 'selected' : '' ?>>Contract
                    </option>
                </select>
            </div>
            <div>
                <label class="block font-medium mb-1">Experience Level</label>
                <select name="experience_level" required class="w-full border px-4 py-2 rounded">
                    <option value="">-- Select --</option>
                    <option value="fresher" <?= $job['experience_level'] === 'fresher' ? 'selected' : '' ?>>Fresher
                    </option>
                    <option value="junior" <?= $job['experience_level'] === 'junior' ? 'selected' : '' ?>>Junior</option>
                    <option value="mid" <?= $job['experience_level'] === 'mid' ? 'selected' : '' ?>>Mid</option>
                    <option value="senior" <?= $job['experience_level'] === 'senior' ? 'selected' : '' ?>>Senior</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="manage_jobs.php"
                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded transition">
                🔙 Back to Job Listing
            </a>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                💾 Save Changes
            </button>
        </div>
    </form>
</div>