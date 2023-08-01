<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Add Admin');

if(isset($_POST['first']))
{
    
    if($_POST['first'] && $_POST['last'] && $_POST['email'])
    {
        
        try {

            if(!$_POST['password']) $_POST['password'] = 'password';

            $query = 'INSERT INTO admins (
                    first,
                    last, 
                    github,
                    email,
                    password,
                    class_id
                ) VALUES (
                    "'.mysqli_real_escape_string($connect, $_POST['first']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['last']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['github']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['email']).'",
                    "'.md5($_POST['password']).'",
                    "'.$_SESSION['admin']['class_id'].'"
                )';
            mysqli_query($connect, $query);

            set_message('Admin has been added!', 'success');

        } catch (Exception $e) {

            set_message('There was an error adding this admin!', 'error');

        }
                
    }
    else
    {
        set_message('There was an error adding this admin!', 'error');
    }

    redirect('console-admin-list.php');

}

include('includes/header.php');

?>

<h1>Add Admin</h1>

<?php check_message(); ?>

<hr>

<form method="post">

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
        Password:
        <br>
        <input type="password" name="password">
    </label>

    <input type="submit" value="Save">

</form>

<hr>

<div class="right">

    <a href="console-admin-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');