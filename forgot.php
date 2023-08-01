<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

if(isset($_SESSION['student'])) redirect('dashboard.php');
if(isset($_SESSION['admin'])) redirect('console-dashboard.php');

define('PAGE_TITLE', 'Forgot Password');

if(isset($_POST['submit']))
{

    $query = 'SELECT *
        FROM students
        WHERE email = "'.mysqli_real_escape_string($connect, $_POST['email']).'"
        AND status = "active"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $student = mysqli_fetch_assoc($result);
        
        $password = rand(100000000, 999999999);

        sendgrid_mail(
            $student['email'], 
            $student['first'].' '.$student['last'],
            'Flow - Forgot Password', 
            'Your Flow password has been reset to: '.$password
        );

        $query = 'UPDATE students SET
            password = "'.md5($password).'"
            WHERE id = "'.$student['id'].'"
            LIMIT 1';
        mysqli_query($connect, $query);

        set_message('A temporary password has been sent to '.$_POST['email'].'!', 'success');
        redirect('/');

    }
    
    $query = 'SELECT *
        FROM admins
        WHERE email = "'.mysqli_real_escape_string($connect, $_POST['email']).'"
        AND status = "active"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $admin = mysqli_fetch_assoc($result);

        sendgrid_mail(
            $admin['email'], 
            $admin['first'].' '.$admin['last'],
            'Flow - Forgot Password', 
            'Your Flow password has been reset to: '.$password
        );

        $query = 'UPDATE admins SET
            password = "'.md5($password).'"
            WHERE id = "'.$admin['id'].'"
            LIMIT 1';
        mysqli_query($connect, $query);

        set_message('A temporary password has been sent to '.$_POST['email'].'!', 'success');
        redirect('/');
        
    }

    set_message('There was no account associated to '.$_POST['email'].'!', 'error');
    redirect('/');

}

include('includes/header.php');

?>

<h1>Forgot Password</h1>

<?php check_message(); ?>

<p>Provide your email address and you will be sent a temporay password:</p>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <label>
        Email:
        <br>
        <input type="email" name="email">
    </label>

    <input type="submit" value="Send Password">

</form>

<hr>

<div class="left">

    <a href="/">Login</a> | <a href="register.php">Register</a>

</div>

<?php

include('includes/footer.php');