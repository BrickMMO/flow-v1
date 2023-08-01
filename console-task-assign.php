<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Assign Task');

if(isset($_POST['submit']))
{

    die('ASSIGN');
    
    set_message('Tasks have been assigned!', 'success');

    redirect('console-task-list.php');

}

include('includes/header.php');

?>

<h1>Add Task</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <?php

    $query = 'SELECT tasks.*,class_task.class_id 
        FROM tasks
        LEFT JOIN class_task
        ON task_id = tasks.id
        AND class_id = "'.$_GET['id'].'"
        ORDER BY name';
    
    $result = mysqli_query($connect, $query);

    ?>

    <?php while($task = mysqli_fetch_assoc($result)): ?>

        <label>
            <input type="checkbox" value="<?=$task['id']?>"<?php if($task['class_id']): ?> selected<?php endif; ?>> <?=$task['name']?>
        </label>

    <?php endwhile; ?>
    
    <input type="submit" value="Assign">

</form>

<hr>

<div class="right">

    <a href="console-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');