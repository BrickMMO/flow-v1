<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();

define('PAGE_TITLE', 'Dashboard');

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
        redirect('console-class-list.php');    

    }

}
else
{

    set_message('There was an error loading this class!', 'error');
    redirect('console-class-list.php');

}

include('includes/header.php');

?>

<?php



?>

<h1><?=$class['code']?> - <?=$class['name']?> (<?=CLASS_SEMESTER[$class['semester']]?> <?=$class['year']?>)</h1>

<?php check_message(); ?>

<hr>

<?php

include('includes/footer.php');