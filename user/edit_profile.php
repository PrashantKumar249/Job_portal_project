<?php
session_start();
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'include/header.php'; // Use your custom header

$jobseeker_id = $_SESSION['jobseeker_id'];

// Fetch jobseeker details
$stmt = $conn->prepare("SELECT * FROM jobseekers WHERE jobseeker_id = ?");
$stmt->bind_param("s", $jobseeker_id);
$stmt->execute();
$result = $stmt->get_result();
$jobseeker = $result->fetch_assoc();

if (!$jobseeker) {
    echo "<p class='text-red-500 text-center mt-4'>Jobseeker not found.</p>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $education = $_POST['education'] ?? '';
    $course = $_POST['course'] ?? '';
    $specialization = $_POST['specialization'] ?? '';
    $role = $_POST['role'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $experience_level = $_POST['experience_level'] ?? '';

    // Handle resume upload
    $resume_path = $jobseeker['resume']; // Keep existing if not uploaded
    if (!empty($_FILES['resume']['name'])) {
        $target_dir = "../uploads/resumes/";
        $resume_name = basename($_FILES["resume"]["name"]);
        $target_file = $target_dir . time() . "_" . $resume_name;
        move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file);
        $resume_path = $target_file;
    }

    $update_sql = "UPDATE jobseekers SET name=?, email=?, phone=?, education=?, course=?, specialization=?, role=?, skills=?, bio=?, experience_level=?, resume=? WHERE jobseeker_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssssssss", $name, $email, $phone, $education, $course, $specialization, $role, $skills, $bio, $experience_level, $resume_path, $jobseeker_id);
    $stmt->execute();

    //header("Location: profile.php");
    //exit();
}
?>

<!-- Tailwind Form UI -->
<div class="max-w-3xl mx-auto p-6 bg-white shadow-lg mt-10 rounded-xl">
    <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Edit Your Profile</h2>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Full Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($jobseeker['name']) ?>"
                    class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($jobseeker['email']) ?>"
                    class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($jobseeker['phone']) ?>"
                    class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Highest Qualification</label>
                <input type="text" name="education" value="<?= htmlspecialchars($jobseeker['education']) ?>"
                    class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Course</label>
                <input type="text" name="course" value="<?= htmlspecialchars($jobseeker['course']) ?>"
                    class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Specialization</label>
                <input type="text" name="specialization" value="<?= htmlspecialchars($jobseeker['specialization']) ?>"
                    class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Role</label>
                <input type="text" name="role" value="<?= htmlspecialchars($jobseeker['role']) ?>"
                    class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Experience Level</label>
                <input type="text" name="experience_level"
                    value="<?= htmlspecialchars($jobseeker['experience_level']) ?>"
                    class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Skills</label>
            <textarea name="skills" rows="3"
                class="w-full border rounded px-3 py-2"><?= htmlspecialchars($jobseeker['skills']) ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Bio</label>
            <textarea name="bio" rows="3"
                class="w-full border rounded px-3 py-2"><?= htmlspecialchars($jobseeker['bio']) ?></textarea>
        </div>


        <div>
            <label class="block text-sm font-medium">Upload Resume (PDF)</label>
            <input type="file" name="resume" accept=".pdf" class="w-full border rounded px-3 py-2">
            <?php if (!empty($jobseeker['resume'])): ?>
                <p class="text-sm text-green-600 mt-1">Current Resume:
                    <a href="<?= $jobseeker['resume'] ?>" target="_blank" class="underline text-blue-600">View</a>
                </p>
            <?php endif; ?>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition">Save
                Changes</button>
        </div>
    </form>
</div>