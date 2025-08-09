<?php
session_start();
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
} 
include '../inc/db.php';

$jobseeker_id = $_SESSION['jobseeker_id'];

$query = "SELECT * FROM jobseekers WHERE jobseeker_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $jobseeker_id);
$stmt->execute();
$result = $stmt->get_result();
$jobseeker = $result->fetch_assoc();
$stmt->close();
$conn->close();

include "include/header.php";
?>


<body class="min-h-screen bg-gradient-to-b from-blue-50 to-blue-200 font-sans">
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-xl p-8 sm:p-12 space-y-8">
            <h2 class="text-4xl font-extrabold text-center text-blue-800 flex items-center justify-center gap-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Jobseeker Profile
            </h2>

            <?php if ($jobseeker): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Full Name -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Full Name
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['name']); ?></h3>
                </div>

                <!-- Email -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['email']); ?></h3>
                </div>

                <!-- Phone -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Phone
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['phone']); ?></h3>
                </div>

                <!-- Education -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                        Education
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['education']); ?></h3>
                </div>

                <!-- Course -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.747 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Course
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['course']); ?></h3>
                </div>

                <!-- Specialization -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Specialization
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['specialization']); ?></h3>
                </div>

                <!-- Role -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Role
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['role']); ?></h3>
                </div>

                <!-- Skills -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Skills
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['skills']); ?></h3>
                </div>

                 <!-- bio -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Bio
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['bio']); ?></h3>
                </div>

                <!-- Experience Level -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Experience Level
                    </p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($jobseeker['experience_level']); ?></h3>
                </div>

                <!-- Resume -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition">
                    <p class="text-sm text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Resume
                    </p>
                    <?php if (!empty($jobseeker['resume'])): ?>
                        <a href="../Uploads/resumes/<?php echo htmlspecialchars($jobseeker['resume']); ?>" target="_blank" class="text-blue-600 font-semibold hover:underline mt-2 inline-block">View Uploaded Resume</a>
                    <?php else: ?>
                        <p class="text-gray-500 italic mt-2">No resume uploaded</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Edit Profile Button -->
            <div class="text-center">
                <a href="edit_profile.php" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-lg font-semibold px-8 py-3 rounded-full transition-all shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m블.707-10.293a1 1 0 00-1.414 0L16 7.586V3a1 1 0 00-1-1h-4a1 1 0 00-1 1v4.586l-2.293-2.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l4-4a1 1 0 000-1.414z"></path>
                    </svg>
                    Edit Your Profile
                </a>
            </div>
            <?php else: ?>
                <p class="text-center text-red-600">Profile not found.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include "include/footer.php"; ?>
</body>
</html>