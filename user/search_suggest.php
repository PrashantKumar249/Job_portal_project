<?php
include '../inc/db.php';

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($conn, $_POST['query']);
    $sql = "SELECT DISTINCT title FROM jobs 
            WHERE title LIKE '%$query%' 
            LIMIT 5";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="suggestion-item px-4 py-2 hover:bg-blue-50 cursor-pointer text-gray-800 text-sm transition-colors duration-150">'
                 . htmlspecialchars($row['title']) .
                 '</div>';
        }
    } else {
        echo '<div class="px-4 py-2 text-gray-500 text-sm">No suggestions found</div>';
    }
}
?>
