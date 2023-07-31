<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Edit Task');

if(isset($_POST['name']))
{
    
    if($_POST['name'] && $_POST['description'] && $_POST['url'])
    {

        try {
        
            $query = 'UPDATE tasks SET 
                name = "'.mysqli_real_escape_string($connect, $_POST['name']).'",
                description = "'.mysqli_real_escape_string($connect, $_POST['description']).'",
                url = "'.mysqli_real_escape_string($connect, $_POST['url']).'"
                WHERE id = "'.$_GET['id'].'"
                LIMIT 1';
            mysqli_query($connect, $query);

            set_message('Task has been edited!', 'success');

        } catch (Exception $e) {

            set_message('There was an error editing this task!', 'error');

        }
    }
    else
    {
        set_message('There was an error editing this task!', 'error');
    }

    redirect('admin-task-list.php');

}
elseif(isset($_GET['id']))
{
    $query = 'SELECT *
        FROM tasks
        WHERE id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {
        $record = mysqli_fetch_assoc($result);
    }
    else
    {
        set_message('There was an error loading this task!', 'error');
        redirect('admin-task-list.php');    
    }
}
else
{
    set_message('There was an error loading this task!', 'error');
    redirect('admin-task-list.php');
}

include('includes/header.php');

?>

<h1>Edit Class</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <label>
        Name:
        <br>
        <input type="text" name="name" value="<?=$record['name']?>">
    </label>

    <label>
        Description:
        <br>
        <textarea name="description"><?=$record['description']?></textarea>
    </label>

    <label>
        URL:
        <br>
        <input type="url" name="url" value="<?=$record['url']?>">
    </label>

    <input type="submit" value="Save">

</form>

<hr>

<div class="right">

    <a href="admin-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');