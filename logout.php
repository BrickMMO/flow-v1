<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', 'Logout');

unset($_SESSION['stadmindent']);

if(isset($_SESSION['student'])) unset($_SESSION['student']);
if(isset($_SESSION['admin'])) unset($_SESSION['admin']);

include('includes/header.php');

?>

<h1>Flow Logout</h1>

<?php check_message(); ?>

<p>You have been logged out!</p>

<hr>

<a href="/">Login</a>

<?php

include('includes/footer.php');