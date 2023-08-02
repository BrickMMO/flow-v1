<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'AssiEnroll Student');

if(isset($_POST['submit']))
{

    if($_POST['class_id'])
    {

        student_enroll($_GET['id'], $_POST['class_id']);

        set_message('Student has been enrolled!', 'success');

    }
    else
    {
        
        set_message('There was an error enrolling this student!', 'error');

    }
    
    redirect('console-student-list.php');

}

include('includes/header.php');

?>

<h1>Enroll Student</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <?php

    $query = 'SELECT classes.*,class_student.class_id 
        FROM classes
        LEFT JOIN class_student
        ON class_id = classes.id
        AND student_id = "'.$_GET['id'].'"
        ORDER BY year, semester, name';
    
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
                    <?php if($class['class_id']): ?> (enrolled)<?php endif; ?>
                </option>
        
            <?php endwhile; ?>
            
        </select>

    </label>

    <input type="submit" value="Enroll">

</form>

<hr>

<div class="right">

    <a href="console-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');