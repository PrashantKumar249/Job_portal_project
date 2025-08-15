<?php
include '../inc/db.php';

$filter_status = isset($_POST['status']) ? trim($_POST['status']) : '';
$job_id        = isset($_POST['job_id']) ? trim($_POST['job_id']) : '';
$company_id    = isset($_POST['company_id']) ? trim($_POST['company_id']) : '';

if ($job_id === '') {
    echo "<tr><td colspan='6' class='text-center text-gray-500'>No Job ID provided</td></tr>";
    exit;
}

// Build query based on filter
if ($filter_status !== '') {
    $stmt = $conn->prepare("
        SELECT
            j.title AS job_title,
            j.job_id,
            js.jobseeker_id,
            js.name AS jobseeker_name,
            js.skills,
            js.resume AS resume,
            a.applied_at,
            a.status
        FROM applications a
        JOIN jobs j 
            ON a.job_id = j.job_id
        JOIN jobseekers js 
            ON a.jobseeker_id = js.jobseeker_id
        WHERE j.company_id = ? 
          AND j.job_id = ? 
          AND a.status = ?
        ORDER BY a.applied_at DESC
    ");
    $stmt->bind_param("sss", $company_id, $job_id, $filter_status);
} else {
    $stmt = $conn->prepare("
        SELECT
            j.title AS job_title,
            j.job_id,
            js.jobseeker_id,
            js.name AS jobseeker_name,
            js.skills,
            js.resume AS resume,
            a.applied_at,
            a.status
        FROM applications a
        JOIN jobs j 
            ON a.job_id = j.job_id
        JOIN jobseekers js 
            ON a.jobseeker_id = js.jobseeker_id
        WHERE j.company_id = ? 
          AND j.job_id = ?
        ORDER BY a.applied_at DESC
    ");
    $stmt->bind_param("ss", $company_id, $job_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr class="hover:bg-gray-50">
            <td class="border border-gray-300 px-2 sm:px-4 py-2 whitespace-nowrap">
                <?= htmlspecialchars($row['jobseeker_name']) ?>
            </td>
            <td class="border border-gray-300 px-2 sm:px-4 py-2">
                <?= htmlspecialchars($row['skills']) ?>
            </td>
            <td class="border border-gray-300 px-2 sm:px-4 py-2">
                <a href="../resumes/<?= htmlspecialchars($row['resume']); ?>" target="_blank" class="inline-block w-full sm:w-auto text-center bg-blue-500 text-white px-3 py-1 rounded 
                     hover:bg-blue-600 transition text-xs sm:text-sm break-words">
                    View Resume
                </a>
            </td>
            <td class="border border-gray-300 px-2 sm:px-4 py-2 flex items-center gap-2">
                <select class="status-dropdown border rounded px-1 py-0.5 text-sm" 
                    data-job-id="<?= $row['job_id'] ?>"
                    data-jobseeker-id="<?= $row['jobseeker_id'] ?>">
                    <?php
                    $statuses = ['pending', 'shortlisted', 'interview', 'hired', 'rejected'];
                    foreach ($statuses as $status) {
                        $selected = ($row['status'] === $status) ? 'selected' : '';
                        echo "<option value='$status' $selected>" . ucfirst($status) . "</option>";
                    }
                    ?>
                </select>
                <button class="update-status bg-green-500 hover:bg-green-600 text-white rounded p-1"
                    title="Update Status" 
                    data-job-id="<?= $row['job_id'] ?>"
                    data-jobseeker-id="<?= $row['jobseeker_id'] ?>">
                    ✅
                </button>
            </td>
            <td class="border border-gray-300 px-2 sm:px-4 py-2 whitespace-nowrap">
                <?= date("d M Y, h:i A", strtotime($row['applied_at'])) ?>
            </td>
            <td class="border border-gray-300 px-2 sm:px-4 py-2 whitespace-nowrap">
                <a href="view_jobseeker_profile.php?id=<?= urlencode($row['jobseeker_id']) ?>" class="inline-block w-full sm:w-auto text-center bg-blue-500 text-white px-3 py-1 rounded 
                      hover:bg-blue-600 transition text-xs sm:text-sm break-words">
                    View Profile
                </a>
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='6' class='border border-gray-300 px-2 sm:px-4 py-2 text-center text-sm sm:text-base'>No applications found</td></tr>";
}

$stmt->close();
?>
