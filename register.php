<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

if(isset($_SESSION['student'])) redirect('dashboard.php');
if(isset($_SESSION['admin'])) redirect('admin-dashboard.php');

define('PAGE_TITLE', 'Registration');

include('includes/header.php');

?>

<h1>Registration</h1>

<?php check_message(); ?>

<p>Registration for BrickMMO Flow is not currently available.</p>
<p>Please contact your instructor to obtain an account.</p>

<hr>

<div class="left">

    <a href="/">Login</a>

</div>

<?php

include('includes/footer.php');