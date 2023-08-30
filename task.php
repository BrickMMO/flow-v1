<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();

define('PAGE_TITLE', 'Task Details');

if(isset($_GET['id']))
{

    $query = 'SELECT tasks.*,class_task.due_at
        FROM tasks
        INNER JOIN class_task
        ON tasks.id = class_task.task_id
        LEFT JOIN student_task
        ON student_task.task_id = tasks.id
        AND student_task.class_id = "'.$_GET['id'].'"
        WHERE class_task.id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $task = mysqli_fetch_assoc($result);
        
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

include('includes/header.php');

?>

<h1><?=$task['name']?></h1>

<?php check_message(); ?>

<hr>

<h2>Due Date: <?=$task['due_at']?></h2>

<?php if(isset($task['completed_at'])): ?>
    <h2>Submitted: <?=format_date($task['completed_at'])?></h2>
<?php elseif(difference_date($task['due_at']) < 0): ?>
    <h2 class="red">Overdue!</h2>
<?php endif; ?>

<p>
    <?=nl2br($task['description'])?>
</p>

<a href="<?=$task['url']?>"><?=$task['url']?></a>

<hr>

<button href="">Mark as Complete</button>

<?php

include('includes/footer.php');