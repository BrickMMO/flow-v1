<?php
include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();
define('PAGE_TITLE', 'Timesheets: Add Entry');
include('includes/header.php');

$currentDate = $_GET['year'] . "-" . $_GET['month'] . "-" . $_GET['day'];
$newDate = new DateTime($currentDate);
$formattedDate = $newDate->format("Y-m-d");

if (isset($_POST['submit'])) {
  if ($_POST['entry_date'] && $_POST['hours'] && !empty($_POST['task']) && !empty($_POST['description'])) {
    try {

      $query = 'INSERT INTO
             entries (
              completed_at,
              `text`,
              `hours`,
              task_id,
              student_id
              )
            VALUES (
            "' . mysqli_real_escape_string($connect, $_POST['entry_date']) . '",
            "' . mysqli_real_escape_string($connect, $_POST['description']) . '",
            "' . $_POST['hours'] . '",
            "' . mysqli_real_escape_string($connect, $_POST['task']) . '",
            "' . $_SESSION['student']['id'] . '"
            )';
      $res = mysqli_query($connect, $query);
      $newdate_entry = new DateTime($_POST['entry_date']);
      $year = $newdate_entry->format('Y');
      $month = $newdate_entry->format('m');
      $day = $newdate_entry->format('d');
      redirect('timesheets_day.php?year=' . $year . '&month=' . $month . '&day=' . $day);
    } catch (Exception $e) {

      set_message('There was an error editing timesheet!', 'error');
    }

  } else {

    set_message('There was an error editing timesheet!', 'error');
  }



}
$query = 'SELECT *
            FROM tasks
            ';
$result = mysqli_query($connect, $query);


?>
<h1>Timesheets: Add New Entry</h1>
<?php check_message(); ?>
<form method="post">
  <label for="date">Date:</label>
  <input type="date" name="entry_date" value="<?= $formattedDate ?>"><br><br>
  <label for="hours">Hours:</label>
  <input type="number" id="hours" name="hours" min="1"><br><br>
  <label for="description">Description</label>
  <textarea name="description" maxlength="255"></textarea>
  <label for="task">Task</label>

  <select id="task" name="task">
    <option value=""></option>
    <?php while ($task = mysqli_fetch_assoc($result)): ?>
      <option value="<?= $task['id'] ?>">
        <?= $task['name'] ?>
      </option>
    <?php endwhile ?>

  </select>


  <input type="submit" name="submit" value="Submit">
</form>

<?php include('includes/footer.php');