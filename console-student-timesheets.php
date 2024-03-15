<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Timesheets: Calendar View');

include('includes/header.php');
$student_id = $_GET['id'];

$entries_query = 'SELECT `entries`.completed_at, SUM(`entries`.hours) as total_hours
FROM `entries`
WHERE deleted_at IS NULL
GROUP BY student_id, completed_at
HAVING student_id = ' . $student_id . '';

$entries_result = mysqli_query($connect, $entries_query);

$student_query = 'SELECT * FROM students WHERE id = ' . $student_id . '';
$student_result = mysqli_query($connect, $student_query);

// get first row from the result
$student = mysqli_fetch_assoc($student_result);
$first_name = $student['first'];
$last_name = $student['last'];
?>

<?php
function build_calendar($month, $year, $entries_result)
{

  // Create array containing abbreviations of days of week.
  $daysOfWeek = array('S', 'M', 'T', 'W', 'T', 'F', 'S');

  // What is the first day of the month in question?
  $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

  // How many days does this month contain?
  $numberDays = date('t', $firstDayOfMonth);

  // Retrieve some information about the first day of the
  // month in question.
  $dateComponents = getdate($firstDayOfMonth);

  // What is the name of the month in question?
  $monthName = $dateComponents['month'];

  // What is the index value (0-6) of the first day of the
  // month in question.
  $dayOfWeek = $dateComponents['wday'];

  // Create the table tag opener and day headers

  $preMonth = $month == 1 ? 12 : intval($month) - 1;
  $preYear = $month == 1 ? intval($year) - 1 : $year;
  $nextMonth = $month == 12 ? 1 : intval($month) + 1;
  $nextYear = $month == 12 ? intval($year) + 1 : $year;
  $calendar = "<table class='calendar'>";
  $calendar .= "<div class='calendar-nav' >
    <a id='prev' href='" . htmlentities($_SERVER['PHP_SELF']) . "?id=" . $_GET['id'] . "&month=" . $preMonth . "&year=" . $preYear . "'>Prev</a>
    " . $year .
    "<a id= 'next' href = '" . htmlentities($_SERVER['PHP_SELF']) . "?id=" . $_GET['id'] . "&month=" . $nextMonth . "&year=" . $nextYear . "'>Next</a>
    </div>";
  $calendar .= "<caption class='calendar-month'>$monthName</caption>";
  $calendar .= "<tr>";

  // Create the calendar headers

  foreach ($daysOfWeek as $day) {
    $calendar .= "<th class='header'>$day</th>";
  }

  // Create the rest of the calendar

  // Initiate the day counter, starting with the 1st.

  $currentDay = 1;

  $calendar .= "</tr><tr>";

  // The variable $dayOfWeek is used to
  // ensure that the calendar
  // display consists of exactly 7 columns.

  if ($dayOfWeek > 0) {
    $calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>";
  }

  $month = str_pad($month, 2, "0", STR_PAD_LEFT);

  while ($currentDay <= $numberDays) {

    // Seventh column (Saturday) reached. Start a new row.

    if ($dayOfWeek == 7) {

      $dayOfWeek = 0;
      $calendar .= "</tr><tr>";

    }

    // Make sure single digit days are preceded with a 0, e.g. 01, 02, 03
    // While double digit days remain the same, e.g. 10, 11, 12
    $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

    $date = "$year-$month-$currentDayRel";
    // Default value for total hours set to 0 in case of no entries for the day
    $total = 0;
    foreach ($entries_result as $row) {
      if ($date == $row['completed_at']) {
        $total = $row['total_hours'];
        break;
      } else {
        $total = 0;
      }
    }
    // Add a day to the calendar
    // The query parameter are student id, year, month, day
    $calendar .= "<td class='day' rel='$date'><a href='" . "console-student-timesheets-day.php" . "?&id=" . $_GET['id'] . "&year=" . $year . "&month=" . $month . "&day=" . $currentDay . "'>$currentDay</a></br>Total Hours: " . "<strong style='font-size: 1.2rem;''>" . $total . "</strong>" . " </td>";

    // Increment counters
    $currentDay++;
    $dayOfWeek++;
  }



  // Complete the row of the last week in month, if necessary

  if ($dayOfWeek != 7) {

    $remainingDays = 7 - $dayOfWeek;
    $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>";

  }

  $calendar .= "</tr>";

  $calendar .= "</table>";

  return $calendar;

}
?>
<div class="console-timesheet-header">
  <h1>Timesheets: Calendar View</h1>
  <h1>
    <?= $first_name . " " . $last_name ?>
  </h1>
</div>
<?php check_message(); ?>
<?php

$dateComponents = getdate();

$month = $dateComponents['mon'];
$year = $dateComponents['year'];

if (isset($_GET['year']) && isset($_GET['month'])) {
  $month = $_GET['month'];
  $year = $_GET['year'];
}

echo build_calendar($month, $year, $entries_result);

?>
<?php include('includes/footer.php');