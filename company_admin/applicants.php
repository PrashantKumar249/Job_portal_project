<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

$company_id = $_SESSION['company_id'];
$job_id = $_GET['job_id'];

// echo $company_id;
// echo $job_id;

// Fetch applications for this company's specific job
$sql = "
SELECT
    j.title AS job_title,
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
";
// echo $sql;


$stmt = $conn->prepare($sql);
// echo $sql;
$stmt->bind_param("ss", $company_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();
// $row = $result->fetch_assoc();

$query1 = "SELECT title FROM jobs WHERE job_id = ?";
$stmt1 = $conn->prepare($query1);
$stmt1->bind_param("s", $job_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$row1 = $result1->fetch_assoc();


?>

<div class="p-4 sm:p-6 overflow-x-auto">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">
        View Applicants for <?= $row1['title'] ?? '' ?>
    </h2>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-gray-300 px-2 sm:px-4 py-2">Jobseeker Name</th>
                    <th class="border border-gray-300 px-2 sm:px-4 py-2">Skills</th>
                    <th class="border border-gray-300 px-2 sm:px-4 py-2">Resume</th>
                    <th class="border border-gray-300 px-2 sm:px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-2 sm:px-4 py-2">Applied At</th>
                    <th class="border border-gray-300 px-2 sm:px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
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

                            <td class="border border-gray-300 px-2 sm:px-4 py-2 flex items-center gap-2">
                                <select class="status-dropdown border rounded px-1 py-0.5 text-sm" data-job-id="<?= $job_id ?>"
                                    data-jobseeker-id="<?= $row['jobseeker_id'] ?>">
                                    <?php
                                    $statuses = ['shortlisted', 'interview', 'hired', 'rejected'];
                                    foreach ($statuses as $status) {
                                        $selected = ($row['status'] === $status) ? 'selected' : '';
                                        echo "<option value='$status' $selected>" . ucfirst($status) . "</option>";
                                    }
                                    ?>
                                </select>

                                <!-- Tick button -->
                                <button class="update-status bg-green-500 hover:bg-green-600 text-white rounded p-1"
                                    title="Update Status" data-job-id="<?= $job_id ?>"
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
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-2 sm:px-4 py-2 text-center text-sm sm:text-base">
                            No applications found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-10 text-center">
        <a href="manage_jobs.php"
            class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded transition">
            🔙 Back to Job Listing
        </a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on("click", ".update-status", function () {
    let btn = $(this);
    let jobId = btn.data("job-id");
    let jobseekerId = btn.data("jobseeker-id");
    let status = btn.closest("td").find(".status-dropdown").val();

    $.ajax({
        url: "update_status.php",
        type: "POST",
        data: {
            job_id: jobId,
            jobseeker_id: jobseekerId,
            status: status
        },
        success: function (response) {
            alert(response); // You can replace with a success toast later
        },
        error: function () {
            alert("Error updating status.");
        }
    });
});
</script>
