<?php
session_start();
if (!isset($_SESSION['company_admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../inc/db.php';
include 'company_admin_header.php';

// Fetch applications with status = 'interview'
$sql = "
    SELECT application_id, jobseeker_id
    FROM applications
    WHERE status = 'interview'
    ORDER BY application_id DESC
";
$result = $conn->query($sql);
?>

<div class="p-4 sm:p-6">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">Schedule Interviews</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-2 py-2">Application ID</th>
                    <th class="border px-2 py-2">Interview Date</th>
                    <th class="border px-2 py-2">Mode</th>
                    <th class="border px-2 py-2">Location / Link</th>
                    <th class="border px-2 py-2">Notes</th>
                    <th class="border px-2 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border px-2 py-2"><?= $row['application_id'] ?></td>
                            <td class="border px-2 py-2">
                                <input type="date" class="border rounded px-2 py-1 interview-date"
                                    data-application-id="<?= $row['application_id'] ?>">
                            </td>
                            <td class="border px-2 py-2">
                                <select class="border rounded px-2 py-1 interview-mode"
                                    data-application-id="<?= $row['application_id'] ?>">
                                    <option value="">Select</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </td>
                            <td class="border px-2 py-2">
                                <input type="text" placeholder="Enter location/link"
                                    class="border rounded px-2 py-1 interview-location"
                                    data-application-id="<?= $row['application_id'] ?>">
                            </td>
                            <td class="border px-2 py-2">
                                <textarea placeholder="Enter notes"
                                    class="border rounded px-2 py-1 interview-notes"
                                    data-application-id="<?= $row['application_id'] ?>"></textarea>
                            </td>
                            <td class="border px-2 py-2">
                                <button class="save-schedule bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                                    data-application-id="<?= $row['application_id'] ?>">
                                    Save
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="border px-2 py-2 text-center">No interviews to schedule</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Display Scheduled Interviews -->
    <h2 class="text-xl sm:text-2xl font-bold mt-8 mb-4">Scheduled Interviews</h2>
    <div class="overflow-x-auto">
        <?php
        $scheduled = $conn->query("
            SELECT * FROM interview_schedule ORDER BY interview_date ASC
        ");
        ?>
        <table class="min-w-full border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-2 py-2">Application ID</th>
                    <th class="border px-2 py-2">Interview Date</th>
                    <th class="border px-2 py-2">Mode</th>
                    <th class="border px-2 py-2">Location / Link</th>
                    <th class="border px-2 py-2">Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($scheduled->num_rows > 0): ?>
                    <?php while ($s = $scheduled->fetch_assoc()): ?>
                        <tr>
                            <td class="border px-2 py-2"><?= $s['application_id'] ?></td>
                            <td class="border px-2 py-2"><?= $s['interview_date'] ?></td>
                            <td class="border px-2 py-2"><?= $s['mode'] ?></td>
                            <td class="border px-2 py-2"><?= $s['location_or_link'] ?></td>
                            <td class="border px-2 py-2"><?= $s['notes'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="border px-2 py-2 text-center">No scheduled interviews</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on("click", ".save-schedule", function () {
    let appId = $(this).data("application-id");
    let date = $(".interview-date[data-application-id='" + appId + "']").val();
    let mode = $(".interview-mode[data-application-id='" + appId + "']").val();
    let location = $(".interview-location[data-application-id='" + appId + "']").val();
    let notes = $(".interview-notes[data-application-id='" + appId + "']").val();

    console.log(appId);
    console.log(date);
    console.log(mode);
    console.log(location);
    console.log(notes);


    if (!date || !mode || !location) {
        alert("Please fill in all required fields.");
        return;
    }

    $.ajax({
        url: "save_schedule.php",
        type: "POST",
        data: {
            application_id: appId,
            interview_date: date,
            mode: mode,
            location_or_link: location,
            notes: notes
        },
        success: function (response) {
            alert(response);
            location.reload(); // Reload to show updated list
        },
        error: function () {
            alert("Error saving schedule.");
        }
    });
});
</script>
