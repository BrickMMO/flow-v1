<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Timesheets: Day View');

include('includes/header.php');

$currentDate = $_GET['year'] . "-" . $_GET['month'] . "-" . $_GET['day'];

$student_id = $_GET['id'];

$query = 'SELECT entries.*, tasks.name AS task_name, students.first AS student_first, students.last AS student_last
            FROM entries
            JOIN tasks ON entries.task_id = tasks.id
            JOIN students ON entries.student_id = students.id
            WHERE student_id = "' . $student_id . '"
            AND completed_at = "' . $currentDate . '"
            AND deleted_at IS NULL
            ';

try {
  $result = mysqli_query($connect, $query);
} catch (Exception $e) {
  set_message('There was an error fetching entries!', 'error');
}

?>

<div class="console-timesheet-header">
  <h1>Timesheets: Entries</h1>
  <h1>
    <?= $_GET["full_name"] ?>
  </h1>
</div>
<?php check_message(); ?>
<table>
  <tr>
    <th>Entry ID</th>
    <th>Student Name</th>
    <th>Task Name</th>
    <th>Hours</th>
    <th>Description</th>
    <th>Completed At</th>
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
    </tr>
  <?php endwhile; ?>
</table>
<?php include('includes/footer.php');