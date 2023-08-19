<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Edit Student');

if(isset($_POST['submit']))
{
    
    if($_POST['first'] && $_POST['last'] && $_POST['email'])
    {

        try 
        {
        
            $query = 'UPDATE students SET 
                first = "'.mysqli_real_escape_string($connect, $_POST['first']).'",
                last = "'.mysqli_real_escape_string($connect, $_POST['last']).'",
                email = "'.mysqli_real_escape_string($connect, $_POST['email']).'",
                github = "'.mysqli_real_escape_string($connect, $_POST['github']).'",
                linkedin = "'.mysqli_real_escape_string($connect, $_POST['linkedin']).'"
                WHERE id = "'.$_GET['id'].'"
                LIMIT 1';
            mysqli_query($connect, $query);

            if(isset($_POST['password']))
            {

                $query = 'UPDATE students SET 
                    password = "'.md5($_POST['password']).'"
                    WHERE id = "'.$_GET['id'].'"
                    LIMIT 1';
                mysqli_query($connect, $query);
                
            }

            set_message('Student has been edited!', 'success');

        }
        catch(Exception $e) 
        {

            set_message('There was an error editing this student!', 'error');

        }
        
    }
    else
    {
        
        set_message('There was an error editing this student!', 'error');

    }

    redirect('console-student-list.php');

}
elseif(isset($_GET['id']))
{

    $query = 'SELECT *
        FROM students
        WHERE id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $student = mysqli_fetch_assoc($result);

    }
    else
    {

        set_message('There was an error loading this student!', 'error');
        redirect('console-student-list.php'); 

    }

}
else
{

    set_message('There was an error loading this student!', 'error');
    redirect('console-student-list.php');
    
}

include('includes/header.php');

?>

<h1>Edit Student</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <label>
        First Name:
        <br>
        <input type="text" name="first" value="<?=$student['first']?>">
    </label>

    <label>
        Last Name:
        <br>
        <input type="text" name="last" value="<?=$student['last']?>">
    </label>

    <label>
        Email:
        <br>
        <input type="email" name="email" value="<?=$student['email']?>">
    </label>

    <label>
        GitHub Username:
        <br>
        <input type="text" name="github" value="<?=$student['github']?>">
    </label>

    <label>
        LinkedIn Username:
        <br>
        <input type="text" name="linkedin" value="<?=$student['linkedin']?>">
    </label>

    <label>
        Password:
        <br>
        <input type="password" name="password">
    </label>

    <input type="submit" value="Save">

</form>

<hr>

<div class="right">

    <a href="console-student-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');