<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();

define('PAGE_TITLE', 'Class Details');

if(isset($_GET['id']))
{

    $query = 'SELECT classes.*
        FROM classes
        INNER JOIN class_student
        ON classes.id = class_id
        AND student_id = "'.$_SESSION['student']['id'].'"
        WHERE classes.id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $class = mysqli_fetch_assoc($result);
        
    }
    else
    {

        set_message('There was an error loading this class!', 'error');
        redirect('dashboard.php');

    }

}
else
{

    set_message('There was an error loading this class!', 'error');
    redirect('dashboard.php');

}

include('includes/header.php');

?>

<h1><?=$class['code']?> - <?=$class['name']?> (<?=CLASS_SEMESTER[$class['semester']]?> <?=$class['year']?>)</h1>

<?php check_message(); ?>

<hr>

<a href="/dashboard.php">Dashboard</a> |
<?=$class['code']?> - <?=$class['name']?>

<hr>

<?php 

$query = 'SELECT *
    FROM student_task
    WHERE class_id = "'.$_GET['id'].'"
    AND student_id = "'.$_SESSION['student']['id'].'"';
$result = mysqli_query($connect, $query);

$completed = mysqli_num_rows($result);

$query = 'SELECT tasks.*,class_task.due_at,student_task.completed_at,class_task.id
    FROM tasks
    INNER JOIN class_task
    ON tasks.id = class_task.task_id
    LEFT JOIN student_task
    ON student_task.task_id = tasks.id
    AND student_task.class_id = "'.$_GET['id'].'"
    AND student_id = "'.$_SESSION['student']['id'].'"
    WHERE class_task.class_id = "'.$_GET['id'].'"
    ORDER BY due_at ASC, name';
$result = mysqli_query($connect, $query);

?>

<?php if(mysqli_num_rows($result)): ?>

    <p>You have <?=number_to_string($completed)?> of <?=number_to_string(mysqli_num_rows($result))?> tasks completed.</p>

    <?php while($task = mysqli_fetch_assoc($result)): ?>

        <div class="card">
            <h2><?=$task['name']?></h2>
            Due: <?=format_date($task['due_at'])?>
            <?php if(isset($task['completed_at'])): ?>
                Submitted: <?=format_date($task['completed_at'])?>
            <?php elseif(difference_date($task['due_at']) < 0): ?>
                <span class="red">Overdue!</span>
            <?php endif; ?>
            <br>
            <a href="task.php?id=<?=$task['id']?>">Assignment Details</a>
        </div>
        
    <?php endwhile; ?>

<?php else: ?>

    <p>You have not yet been assigned any tasks for this course.</p> 

<?php endif; ?>    

<?php

include('includes/footer.php');