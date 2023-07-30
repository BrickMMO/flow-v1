<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

if(isset($_SESSION['student'])) unset($_SESSION['student']);
if(isset($_SESSION['admin'])) unset($_SESSION['admin']);

define('PAGE_TITLE', 'Logout');

include('includes/header.php');

?>

<h1>Logout</h1>

<?php check_message(); ?>

<p>You have been logged out!</p>

<hr>

<div class="left">

    <a href="/">Login</a>

</div>

<?php

include('includes/footer.php');