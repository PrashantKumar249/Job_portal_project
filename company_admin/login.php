<?php
session_start();
include '../inc/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM company_admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $admin['password'] === $password) {
        $_SESSION['company_admin_id'] = $admin['company_admin_id'];
        $_SESSION['company_admin_name'] = $admin['name'];
        $_SESSION['company_id'] = $admin['company_id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* CSS for the animation */
        .slide-out-left {
            animation: slideOutLeft 0.5s ease-in-out forwards;
        }

        @keyframes slideOutLeft {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div id="login-container" class="bg-white shadow-xl rounded-xl w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 overflow-hidden">
            <!-- Left Info Panel -->
            <div class="bg-green-50 p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Why Join NaukriPro?</h2>
                <ul class="text-sm text-gray-600 space-y-3">
                    <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Post and manage job listings with ease</li>
                    <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Discover the best-fit talent for your company</li>
                    <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Streamline your hiring process & team collaboration</li>
                    <li class="flex items-start gap-2"><span class="text-green-600">✔</span> Track applicant progress from one central dashboard</li>
                </ul>
                <a href="register.php"
                   class="mt-6 inline-block text-center bg-white text-green-600 font-medium border border-green-600 px-6 py-2 rounded hover:bg-green-600 hover:text-white transition">
                    Create your Company Account</a>
            </div>

            <div class="p-8">
                <!-- User type selection buttons -->
                <div class="flex mb-6 text-sm font-medium">
                    <button id="job-seeker-btn" class="flex-1 text-center py-2 px-4 rounded-l-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors duration-300">
                        Job Seeker
                    </button>
                    <button class="flex-1 text-center py-2 px-4 rounded-r-lg bg-green-600 text-white transition-colors duration-300">
                        Job Provider
                    </button>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 mb-6">Login</h2>

                <?php if (!empty($error)): ?>
                    <div class="bg-red-100 text-red-600 px-4 py-2 rounded mb-4 text-sm">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition duration-300 shadow">
                        🔐 Login
                    </button>
                </form>

                <!-- Register Link -->
                <div class="text-center pt-4 border-t">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="register.php" class="text-green-700 font-medium hover:underline">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('job-seeker-btn').addEventListener('click', function() {
            const container = document.getElementById('login-container');
            container.classList.add('slide-out-left');
            setTimeout(() => {
                window.location.href = '../user/login.php';
            }, 100); // Wait for the animation to finish (0.5s)
        });
    </script>
</body>
</html>
