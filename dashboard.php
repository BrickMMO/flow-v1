<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();

define('PAGE_TITLE', 'Dashboard');

include('includes/header.php');

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

<?php

include('includes/footer.php');