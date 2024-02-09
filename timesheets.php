<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();
define('PAGE_TITLE', 'Timesheets Calendar');
$query = 'SELECT completed_at , sum(hours) as total_hours 
          FROM `entries` 
          WHERE deleted_at IS NULL
          Group by student_id, completed_at 
          having student_id="' . $_SESSION['student']['id'] . '" ';
$result = mysqli_query($connect, $query);

include('includes/header.php');
function build_calendar($month, $year,$result)
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
    <a id='prev' href='" . htmlentities($_SERVER['PHP_SELF']) . "?month=" . $preMonth . "&year=" . $preYear . "'>Prev</a>
    " . $year .
          "<a id= 'next' href = '" . htmlentities($_SERVER['PHP_SELF']) . "?month=" . $nextMonth . "&year=" . $nextYear . "'>Next</a>
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

          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

          $date = "$year-$month-$currentDayRel";
          foreach($result as $row){
               if($date == $row['completed_at']){
                    $total = $row['total_hours'];
                    break;
               }
               else{
                    $total=0;

               }
               
          }
          $calendar .= "<td class='day' rel='$date'><a href='" . "timesheets_day.php" . "?year=" . $year . "&month=" . $month . "&day=" . $currentDay . "'>$currentDay</a></br>Total Hours:".$total." </td>";
          

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
<h1>Timesheets: Calendar</h1>
<?php check_message(); ?>
<?php

$dateComponents = getdate();

$month = $dateComponents['mon'];
$year = $dateComponents['year'];

if (isset($_GET['year']) && isset($_GET['month'])) {
     $month = $_GET['month'];
     $year = $_GET['year'];
}

echo build_calendar($month, $year,$result);

?>

<?php include('includes/footer.php');