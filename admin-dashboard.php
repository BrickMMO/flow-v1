<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Dashboard');

include('includes/header.php');

?>

<h1>Admin Dashboard</h1>

<?php check_message(); ?>

<hr>

<?php

include('includes/footer.php');