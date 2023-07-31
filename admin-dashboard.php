<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Dashboard');

include('includes/header.php');

?>

<h1>Admin Dashboard</h1>

<?php check_message(); ?>

<hr>

<a href="admin-class-list.php">Classes</a>
<br>
<a href="admin-student-list.php">Students</a>
<br>
<a href="admin-task-list.php">Tasks</a>

<?php

include('includes/footer.php');