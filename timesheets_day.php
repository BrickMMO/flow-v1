<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();
define('PAGE_TITLE', 'Timesheets Day');

if (isset($_GET['delete'])) {
    delete_entry($_GET['delete']);

    set_message('Entry has been deleted!');
    redirect('timesheets_day.php?year=' . $_GET['year'] . "&month=" . $_GET['month'] . "&day=" . $_GET['day']);
}

include('includes/header.php');

$currentDate = $_GET['year'] . "-" . $_GET['month'] . "-" . $_GET['day'];

$query = 'SELECT entries.*, tasks.name AS task_name, students.first AS student_first, students.last AS student_last
            FROM entries
            JOIN tasks ON entries.task_id = tasks.id
            JOIN students ON entries.student_id = students.id
            WHERE student_id = "' . $_SESSION['student']['id'] . '"
            AND completed_at = "' . $currentDate . '"
            ';

$result = mysqli_query($connect, $query);

?>
<h1>Timesheets: Entries</h1>
<?php check_message(); ?>
<table>
    <tr>
        <th>Entry ID</th>
        <th>Student Name</th>
        <th>Task Name</th>
        <th>Hours</th>
        <th>Description</th>
        <th>Completed At</th>
        <th>Action</th>
    </tr>
    <?php while ($entries = mysqli_fetch_assoc($result)): ?>

        <tr>
            <td>
                <?= $entries['id'] ?> <br>
            </td>
            <td>
                <?= $entries['student_first'] . " " . $entries['student_last'] ?><br>
            </td>
            <td>
                <?= $entries['task_name'] ?><br>
            </td>
            <td>
                <?= $entries['hours'] ?><br>
            </td>
            <td>
                <?= $entries['text'] ?><br>
            </td>
            <td>
                <?= $entries['completed_at'] ?><br>
            </td>
            <td>
                <a
                    href="timesheets_day.php?delete=<?= $entries['id'] . "&year=" . $_GET['year'] . "&month=" . $_GET['month'] . "&day=" . $_GET['day'] ?>">&#10006;
                    Delete</a><br>
            </td>
        </tr>

    <?php endwhile; ?>
</table>
<button>
    <a href="timesheets_add.php?year=<?= $_GET['year'] ?>&month=<?= $_GET['month'] ?>&day=<?= $_GET['day'] ?>">Add
        Entry</a>
</button>
<?php include('includes/footer.php');