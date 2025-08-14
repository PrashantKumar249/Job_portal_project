<?php
session_start();
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
} 
include '../inc/db.php'; // update path as needed

$query = "SELECT * FROM companies";
$result = mysqli_query($conn, $query);

include "include/header.php";
?>


<body class="min-h-screen bg-gradient-to-b from-blue-50 to-blue-200 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-extrabold text-center text-blue-800 mb-10 flex items-center justify-center gap-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Explore Companies
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($company = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                    <!-- Logo -->
                    <div class="flex justify-center mb-4">
                        <?php if (!empty($company['logo'])): ?>
                            <img src="../Uploads/company_logo/<?php echo htmlspecialchars($company['logo']); ?>" alt="Company Logo" class="w-24 h-24 object-contain rounded-full border-2 border-blue-100">
                        <?php else: ?>
                            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-medium">
                                No Logo
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Company Info -->
                    <h2 class="text-xl font-semibold text-center text-gray-800 mb-2"><?php echo htmlspecialchars($company['name']); ?></h2>
                    <p class="text-sm text-center text-blue-600 font-medium italic mb-4"><?php echo htmlspecialchars($company['industry']); ?></p>
                    
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3"><?php echo htmlspecialchars($company['description']); ?></p>

                    <!-- Details -->
                    <ul class="text-sm text-gray-600 space-y-2 mb-5">
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span><strong>Address:</strong> <?php echo htmlspecialchars($company['address']); ?></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span><strong>Phone:</strong> <?php echo htmlspecialchars($company['contact_phone']); ?></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($company['contact_email']); ?>" class="text-blue-600 hover:underline"><?php echo htmlspecialchars($company['contact_email']); ?></a></span>
                        </li>
                    </ul>

                    <!-- Website Button -->
                    <?php if (!empty($company['website'])): ?>
                        <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank" class="inline-flex items-center justify-center w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Visit Website
                        </a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($result) === 0): ?>
                <p class="text-center text-gray-600 col-span-full">No companies found.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include "include/footer.php"; ?>
</body>
</html>