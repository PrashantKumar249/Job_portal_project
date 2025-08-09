<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Superadmin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header -->
<header class="bg-gradient-to-r from-blue-700 to-indigo-700 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col sm:flex-row justify-between items-center">
        <!-- Left: Title -->
        <div class="text-center sm:text-left">
            <h1 class="text-3xl font-extrabold tracking-wide">Superadmin Dashboard</h1>
            <p class="text-sm text-blue-200 mt-1">Manage everything at a glance</p>
        </div>

        <!-- Right: Welcome & Logout -->
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-center gap-3 text-sm">
            <span class="text-white bg-blue-600 px-3 py-1 rounded-full shadow">
                👋 Welcome, <strong><?= htmlspecialchars($_SESSION['superadmin_name']); ?></strong>
            </span>
            <a href="logout.php" class="bg-red-500 px-4 py-1.5 rounded hover:bg-red-600 transition-all text-white font-medium shadow">
                Logout
            </a>
        </div>
    </div>
</header>
