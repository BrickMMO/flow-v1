<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Add Class');

if(isset($_POST['name']))
{
    
    if($_POST['name'] && $_POST['year'] && $_POST['year'] && $_POST['semester'])
    {

        try {

            $query = 'INSERT INTO classes (
                    name, 
                    code,
                    year,
                    semester
                ) VALUES (
                    "'.mysqli_real_escape_string($connect, $_POST['name']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['code']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['year']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['semester']).'"
                )';
            mysqli_query($connect, $query);

            set_message('Class has been added!', 'success');

        } catch (Exception $e) {

            set_message('There was an error adding this class!', 'error');

        }
                
    }
    else
    {
        set_message('There was an error adding this class!', 'error');
    }

    redirect('admin-class-list.php');

}

include('includes/header.php');

?>

<h1>Add Class</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <label>
        Name:
        <br>
        <input type="text" name="name">
    </label>

    <label>
        Code:
        <br>
        <input type="text" name="code">
    </label>

    <label>
        Year:
        <br>
        <input type="year" name="year">
    </label>

    <label>
        Semester:
        <br>
        <?php select('semester', CLASS_SEMESTER); ?>
    </label>

    <input type="submit" value="Save">

</form>

<hr>

<div class="right">

    <a href="admin-class-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');