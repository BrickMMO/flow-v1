<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', 'Registration');

if(isset($_SESSION['student'])) redirect('dashboard.php');
if(isset($_SESSION['admin'])) redirect('admin-dashboard.php');

include('includes/header.php');

?>

<h1>Registration</h1>

<?php check_message(); ?>

<p>Registration for BrickMMO Flow is not currently available.</p>
<p>Please contact your instructor to obtain an account.</p>

<hr>

<a href="/">Login</a>

<?php

include('includes/footer.php');