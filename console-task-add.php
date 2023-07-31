<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Add Task');

if(isset($_POST['name']))
{
    
    if($_POST['name'] && $_POST['description'] && $_POST['url'])
    {
        
        try {

            $query = 'INSERT INTO tasks (
                    name, 
                    description,
                    url
                ) VALUES (
                    "'.mysqli_real_escape_string($connect, $_POST['name']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['description']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['url']).'"
                )';
            mysqli_query($connect, $query);

            set_message('Task has been added!', 'success');

        } catch (Exception $e) {

            set_message('There was an error adding this task!', 'error');

        }
                
    }
    else
    {
        set_message('There was an error adding this task!', 'error');
    }

    redirect('console-task-list.php');

}

include('includes/header.php');

?>

<h1>Add Task</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <label>
        Name:
        <br>
        <input type="text" name="name">
    </label>

    <label>
        Description:
        <br>
        <textarea name="description"></textarea>
    </label>

    <label>
        URL:
        <br>
        <input type="url" name="url">
    </label>

    <input type="submit" value="Save">

</form>

<hr>

<div class="right">

    <a href="console-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');