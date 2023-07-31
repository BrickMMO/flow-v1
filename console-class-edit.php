<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Edit Class');

if(isset($_POST['name']))
{
    
    if($_POST['name'] && $_POST['year'] && $_POST['year'] && $_POST['semester'])
    {

        try {
        
            $query = 'UPDATE classes SET 
                name = "'.mysqli_real_escape_string($connect, $_POST['name']).'",
                code = "'.mysqli_real_escape_string($connect, $_POST['code']).'",
                year = "'.mysqli_real_escape_string($connect, $_POST['year']).'",
                semester = "'.mysqli_real_escape_string($connect, $_POST['semester']).'"
                WHERE id = "'.$_GET['id'].'"
                LIMIT 1';
            mysqli_query($connect, $query);

            set_message('Class has been edited!', 'success');

        } catch (Exception $e) {

            set_message('There was an error editing this class!', 'error');

        }
    }
    else
    {
        set_message('There was an error editing this class!', 'error');
    }

    redirect('console-class-list.php');

}
elseif(isset($_GET['id']))
{
    $query = 'SELECT *
        FROM classes
        WHERE id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {
        $record = mysqli_fetch_assoc($result);
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
        Code:
        <br>
        <input type="text" name="code" value="<?=$record['code']?>">
    </label>

    <label>
        Year:
        <br>
        <input type="year" name="year" value="<?=$record['year']?>">
    </label>

    <label>
        Semester:
        <br>
        <?php select('semester', CLASS_SEMESTER, $record['semester']); ?>
    </label>

    <input type="submit" value="Save">

</form>

<hr>

<div class="right">

    <a href="console-class-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');