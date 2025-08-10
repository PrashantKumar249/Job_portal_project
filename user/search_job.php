<?php
include '../inc/db.php';

$job = isset($_POST['job']) ? mysqli_real_escape_string($conn, $_POST['job']) : '';
$location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : '';

$sql = "SELECT * FROM jobs WHERE 1";
if (!empty($job)) {
    $sql .= " AND (title LIKE '%$job%' OR company_name LIKE '%$job%' OR description LIKE '%$job%')";
}
if (!empty($location)) {
    $sql .= " AND location LIKE '%$location%'";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '
        <div class="bg-white shadow-md p-4 rounded-lg mb-4">
            <h2 class="text-gray-600 text-xl font-bold">' . htmlspecialchars($row['title']) . '</h2>
            <p class="text-gray-600">' . htmlspecialchars($row['company_name']) . ' - ' . htmlspecialchars($row['location']) . '</p>
            <p class="text-gray-500 mt-2">' . substr(htmlspecialchars($row['description']), 0, 100) . '...</p>
            <a href="job_details.php?id=' . $row['id'] . '" class="text-blue-600 mt-2 inline-block">View Details</a>
        </div>';
    }
} else {
    echo '<p class="text-gray-500">No jobs found.</p>';
}
?>
