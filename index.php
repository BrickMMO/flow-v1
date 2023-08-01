<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

if(isset($_SESSION['student'])) redirect('dashboard.php');
if(isset($_SESSION['admin'])) redirect('console-dashboard.php');

define('PAGE_TITLE', 'Login');

if(isset($_POST['submit']))
{

    $query = 'SELECT *
        FROM students
        WHERE email = "'.mysqli_real_escape_string($connect, $_POST['email']).'"
        AND password = "'.md5($_POST['password']).'"
        AND status = "active"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $student = mysqli_fetch_assoc($result);

        $_SESSION['student']['id'] = $student['id'];

        set_message('You have been logged in!', 'success');
        redirect('dashboard.php');

    }
    
    $query = 'SELECT *
        FROM admins
        WHERE email = "'.mysqli_real_escape_string($connect, $_POST['email']).'"
        AND password = "'.md5($_POST['password']).'"
        AND status = "active"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $admin = mysqli_fetch_assoc($result);

        $_SESSION['admin']['id'] = $admin['id'];
        $_SESSION['admin']['class_id'] = $admin['class_id'];

        set_message('You have been logged in!', 'success');
        redirect('console-dashboard.php');

    }

}

include('includes/header.php');

?>

<h1>Flow Login</h1>

<?php check_message(); ?>

<p>Welcome to the BrickMMO project management application.</p>

<p>Login using your Humber email address:</p>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <label>
        Email:
        <br>
        <input type="email" name="email">
    </label>

    <label>
        Password:
        <br>
        <input type="password" name="password">
    </label>

    <input type="submit" value="Login">

</form>

<hr>

<div class="left">

    <a href="forgot.php">Forgot Password</a> | <a href="register.php"> Register</a>

</div>

<?php

include('includes/footer.php');