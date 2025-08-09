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
</head>
<body class="bg-gradient-to-br from-green-100 via-green-200 to-green-300 min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 space-y-6">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-green-700 mb-2">Company Admin Login</h1>
            <p class="text-sm text-gray-500">Access your company dashboard</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
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

</body>
</html>
