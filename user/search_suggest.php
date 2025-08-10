<?php
include '../inc/db.php';

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($conn, $_POST['query']);
    $sql = "SELECT DISTINCT title, location FROM jobs WHERE (title LIKE '%$query%' OR location LIKE '%$query%') LIMIT 5";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="suggestion-item p-2 hover:bg-gray-100 cursor-pointer">' . htmlspecialchars($row['title']) . '</div>';
        }
    } else {
        echo '<div class="p-2 text-gray-500">No suggestions found</div>';
    }
}
?>
