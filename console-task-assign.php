<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Assign Task');

if(isset($_POST['submit']))
{

    if($_POST['classes'])
    {

        $query = 'DELETE FROM class_task
            WHERE class_id NOT IN ('.implode(',', $_POST['classes']).')
            AND task_id = "'.$_GET['id'].'"';
        mysqli_query($connect, $query);

        print_r($_POST);

        foreach($_POST['classes'] as $class_id)
        {

            $query = 'INSERT IGNORE INTO class_task (
                    class_id,
                    task_id
                ) VALUES (
                    "'.$class_id.'",
                    "'.$_GET['id'].'"
                )';
            mysqli_query($connect, $query);

        }

        set_message('Task has been assigned!', 'success');

    }
    else
    {

        $query = 'DELETE FROM class_task
            WHERE task_id = "'.$_GET['id'].'"';
        mysqli_query($connect, $query);
        
        set_message('Task has been removed from all classes!', 'success');

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

    <?php while($class = mysqli_fetch_assoc($result)): ?>

        <label>
            <input type="checkbox" name="classes[]" value="<?=$class['id']?>"<?php if($class['class_id']): ?> checked<?php endif; ?>>
            <?=$class['name']?> - <?=CLASS_SEMESTER[$class['semester']]?> <?=$class['year']?>
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