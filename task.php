<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();

define('PAGE_TITLE', 'Task Details');

if(isset($_GET['id']))
{

    $query = 'SELECT tasks.*,
            class_task.due_at,
            class_task.class_id,
            class_task.task_id,
            student_task.completed_at
        FROM tasks
        INNER JOIN class_task
        ON tasks.id = class_task.task_id
        LEFT JOIN student_task
        ON student_task.task_id = class_task.task_id
        AND student_task.class_id = class_task.class_id
        WHERE class_task.id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $task = mysqli_fetch_assoc($result);

        if(isset($_GET['complete']))
        {

            $query = 'INSERT IGNORE INTO student_task (
                    student_id,
                    task_id,
                    class_id,
                    completed_at
                ) VALUES (
                    "'.$_SESSION['student']['id'].'",
                    "'.$task['task_id'].'",
                    "'.$task['class_id'].'",
                    NOW()
                )';
            mysqli_query($connect, $query);

            set_message('Task has been marked as complete!', 'success');
            redirect('task.php?id='.$_GET['id']);

        }
        
    }
    else
    {

        set_message('There was an error loading this task!', 'error');
        redirect('dashboard.php');

    }

}
else
{

    set_message('There was an error loading this task!', 'error');
    redirect('dashboard.php');

}


$query = 'SELECT classes.*
    FROM classes
    INNER JOIN class_student
    ON classes.id = class_id
    AND student_id = "'.$_SESSION['student']['id'].'"
    WHERE classes.id = "'.$task['class_id'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

$class = mysqli_fetch_assoc($result);

include('includes/header.php');

?>

<h1><?=$task['name']?></h1>

<?php check_message(); ?>

<hr>

<a href="/dashboard.php">Dashboard</a> |
<a href="/class.php?id=<?=$class['id']?>"><?=$class['code']?> - <?=$class['name']?></a> | 
<?=$task['name']?>

<hr>

<h2>Due Date: <?=$task['due_at']?></h2>

<?php if(isset($task['completed_at'])): ?>
    <p class="green">This assignment was marked as complete on <?=format_date($task['completed_at'])?>.</p>
<?php elseif(difference_date($task['due_at']) < 0): ?>
    <p class="red">This assignment is overdue!</span></p>
<?php endif; ?>

<p>
    <?=nl2br($task['description'])?>
</p>

<a href="<?=$task['url']?>"><?=$task['url']?></a>

<?php if(!isset($task['completed_at'])): ?>

    <hr>

    <button onclick="window.location.href='task.php?id=<?=$_GET['id']?>&amp;complete';">
        Mark as Complete
    </button>
    
<?php endif; ?>

<?php

include('includes/footer.php');