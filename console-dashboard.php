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

<div class="panels">
    <div>
        <a href="console-class-list.php">Classes</a>
    </div>
    <div>
        <a href="console-student-list.php">Students</a>
    </div>
    <div>
        <a href="console-task-list.php">Tasks</a>
    </div>
    <div>
        <a href="console-skill-list.php">Skills</a>
    </div>
    <div>
        <a href="console-admin-list.php">Admins</a>
    </div>
    <div>
        <a href="questions.php">Questions</a>
    </div>
</div>


<?php

include('includes/footer.php');