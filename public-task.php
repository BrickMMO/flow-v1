<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', 'Task Details');

if(isset($_GET['id']))
{

    $query = 'SELECT *
        FROM tasks
        WHERE id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $task = mysqli_fetch_assoc($result);
        
    }
    else
    {

        set_message('There was an error loading this task!', 'error');
        redirect('/');

    }

}
else
{

    set_message('There was an error loading this task!', 'error');
    redirect('/');

}

include('includes/header.php');

?>

<h1><?=$task['name']?></h1>

<?php check_message(); ?>

<hr>

<p>
    <?=nl2br($task['description'])?>
</p>

<a href="<?=$task['url']?>"><?=$task['url']?></a>

<?php

include('includes/footer.php');     