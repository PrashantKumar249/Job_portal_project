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

<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">View Applicants for <?= $row1['title'] ?? '' ?> </h2>

    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="border border-gray-300 px-4 py-2">Jobseeker Name</th>
                <th class="border border-gray-300 px-4 py-2">Skills</th>
                <th class="border border-gray-300 px-4 py-2">Resume</th>
                <th class="border border-gray-300 px-4 py-2">status</th>
                <th class="border border-gray-300 px-4 py-2">Applied At</th>
                <th class="border border-gray-300 px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['jobseeker_name']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['skills']) ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="../resumes/<?php echo htmlspecialchars($row['resume']); ?>" target="_blank"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">
                                View Resume
                            </a>
                        </td>

                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['status']) ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <?= date("d M Y, h:i A", strtotime($row['applied_at'])) ?>
                        </td>
                        <td class="border border-gray-300 px-2 py-4">
                            <a href="view_jobseeker_profile.php?id=<?= urlencode($row['jobseeker_id']) ?>"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                View Profile
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="border border-gray-300 px-4py-2 text-center">No applications found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>