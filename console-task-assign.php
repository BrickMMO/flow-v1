<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Assign Task');

if(isset($_POST['submit']))
{

    if($_POST['class_id'] && $_POST['due_at'])
    {

        task_assign($_GET['id'], $_POST['class_id'], $_POST['due_at']);

        set_message('Task has been assigned!', 'success');

    }
    else
    {
        
        set_message('There was an error assigning this task!', 'error');

    }
    
    redirect('console-task-list.php');

}

include('includes/header.php');

?>

<h1>Assign Task</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <?php

    $query = 'SELECT classes.*,class_task.class_id 
        FROM classes
        LEFT JOIN class_task
        ON class_id = classes.id
        AND task_id = "'.$_GET['id'].'"
        ORDER BY name';
    
    $result = mysqli_query($connect, $query);

    ?>

    <label>

        Class:
        <br>
        <select name="class_id">
            <option value=""></option>    

            <?php while($class = mysqli_fetch_assoc($result)): ?>

                <option value="<?=$class['id']?>">
                    <?=$class['name']?> - <?=CLASS_SEMESTER[$class['semester']]?> <?=$class['year']?>
                    <?php if($class['class_id']): ?> (assigned)<?php endif; ?>
                </option>

            <?php endwhile; ?>
            
        </select>

    </label>

    <label>
        Due:
        <br>
        <input type="date" name="due_at">
    </label>

    <input type="submit" value="Assign">

</form>

<hr>

<div class="right">

    <a href="console-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');