<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Edit Admin');

if(isset($_POST['submit']))
{
    
    if($_POST['first'] && $_POST['last'] && $_POST['email'])
    {

        try 
        {
        
            $query = 'UPDATE admins SET 
                first = "'.mysqli_real_escape_string($connect, $_POST['first']).'",
                last = "'.mysqli_real_escape_string($connect, $_POST['last']).'",
                email = "'.mysqli_real_escape_string($connect, $_POST['email']).'",
                github = "'.mysqli_real_escape_string($connect, $_POST['github']).'"
                WHERE id = "'.$_GET['id'].'"
                LIMIT 1';
            mysqli_query($connect, $query);

            if(isset($_POST['password']))
            {

                $query = 'UPDATE admins SET 
                    password = "'.md5($_POST['password']).'"
                    WHERE id = "'.$_GET['id'].'"
                    LIMIT 1';
                mysqli_query($connect, $query);

            }

            set_message('Admin has been edited!', 'success');

        }
        catch(Exception $e) 
        {

            set_message('There was an error editing this admin!', 'error');

        }
        
    }
    else
    {

        set_message('There was an error editing this admin!', 'error');

    }

    redirect('console-admin-list.php');

}
elseif(isset($_GET['id']))
{

    $query = 'SELECT *
        FROM admins
        WHERE id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $record = mysqli_fetch_assoc($result);

    }
    else
    {

        set_message('There was an error loading this admin!', 'error');
        redirect('console-task-list.php');    

    }

}
else
{

    set_message('There was an error loading this admin!', 'error');
    redirect('console-task-list.php');

}

include('includes/header.php');

?>

<h1>Edit Admin</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <label>
        First Name:
        <br>
        <input type="text" name="first" value="<?=$record['first']?>">
    </label>

    <label>
        Last Name:
        <br>
        <input type="text" name="last" value="<?=$record['last']?>">
    </label>

    <label>
        Email:
        <br>
        <input type="email" name="email" value="<?=$record['email']?>">
    </label>

    <label>
        GitHub Username:
        <br>
        <input type="text" name="github" value="<?=$record['github']?>">
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