<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Add Student');

if(isset($_POST['submit']))
{

    if($_POST['first'] && $_POST['last'] && $_POST['email'])
    {
        
        try 
        {

            if(!$_POST['password']) $_POST['password'] = 'password';

            $query = 'INSERT INTO students (
                    first,
                    last, 
                    github,
                    linkedin,
                    email,
                    password
                ) VALUES (
                    "'.mysqli_real_escape_string($connect, $_POST['first']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['last']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['github']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['linkedin']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['email']).'",
                    "'.md5($_POST['password']).'"
                )';
            mysqli_query($connect, $query);

            set_message('Student has been added!', 'success');

        }
        catch(Exception $e) 
        {

            set_message('There was an error adding this student!', 'error');

        }
                
    }
    else
    {

        set_message('There was an error adding this student!', 'error');

    }

    redirect('console-student-list.php');

}

include('includes/header.php');

?>

<h1>Add Student</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <label>
        First Name:
        <br>
        <input type="text" name="first">
    </label>

    <label>
        Last Name:
        <br>
        <input type="text" name="last">
    </label>

    <label>
        Email:
        <br>
        <input type="email" name="email">
    </label>

    <label>
        GitHub Username:
        <br>
        <input type="text" name="github">
    </label>

    <label>
        LinkedIn Username:
        <br>
        <input type="text" name="linkedin">
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