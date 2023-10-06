<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');


secure();

define('PAGE_TITLE', 'Dashboard');

include('includes/header.php');

$student = fetch_student();

?>

<h1>Student Dashboard</h1>

<?php check_message(); ?>

<hr>

<?php

$query = 'SELECT classes.*,(
        SELECT COUNT(*)
        FROM class_task
        WHERE class_id = classes.id
    ) AS tasks,(
        SELECT COUNT(*)
        FROM student_task
        WHERE class_id = classes.id
        AND student_id = "'.$_SESSION['student']['id'].'"
    ) AS completed,(
        SELECT COUNT(*)
        FROM class_task
        LEFT JOIN student_task
        ON student_task.student_id = "'.$_SESSION['student']['id'].'"
        AND student_task.class_id = class_task.class_id
        AND student_task.task_id = class_task.task_id
        WHERE class_task.class_id = classes.id
        AND due_at < NOW()
        AND completed_at IS NULL
    ) AS overdue
    FROM classes
    INNER JOIN class_student
    ON class_id = classes.id
    WHERE student_id = "'.$_SESSION['student']['id'].'"
    ORDER BY year, semester, name';
$result = mysqli_query($connect, $query);

?>

<h2>Welcome <?=$student['first']?> <?=$student['last']?>,</h2>

<?php if(mysqli_num_rows($result)): ?>

    <p>You are currently enrolled in <?=number_to_string(mysqli_num_rows($result))?> class<?=mysqli_num_rows($result) != 1 ? 'es' : ''?>.</p>
        
    <?php while($class = mysqli_fetch_assoc($result)): ?>

        <div class="card">
            <h2><?=$class['code']?> - <?=$class['name']?> (<?=CLASS_SEMESTER[$class['semester']]?> <?=$class['year']?>)</h2>
            <?php if($class['overdue'] > 0): ?>
                <p class="red">This class has overdue tasks!
            <?php endif; ?>
            <p>
                Tasks: <?=$class['completed']?>/<?=$class['tasks']?>
                <br>
                <a href="class.php?id=<?=$class['id']?>">Work on this Class</a>
            </p>
        </div>

    <?php endwhile; ?>

<?php else: ?>

    <p>Please contact your instructor to have your account enrolled in a class.</p>

<?php endif; ?>    

<hr>

<div class="panels">
    <div>
        <a href="account.php">My Account</a>
    </div>
    <div>
        <a href="skills.php">Skills</a>
    </div>
    <div>
        <a href="questions.php">My Questions</a>
    </div>
    <div>
        <a href="timesheets.php">Timesheets</a>
    </div>
</div>

<?php

include('includes/footer.php');