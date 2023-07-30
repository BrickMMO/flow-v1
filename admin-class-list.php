<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

if(isset($_GET['select']))
{
    $query = 'UPDATE admins SET 
        class_id = "'.$_GET['select'].'"
        WHERE admins.id = "'.$_SESSION['admin']['id'].'"
        LIMIT 1';
    mysqli_query($connect, $query);

    set_message('Class has been changed!');
    redirect('admin-class-list.php');
}

define('PAGE_TITLE', 'DasChange Classhboard');

include('includes/header.php');

?>

<h1>Select Class</h1>

<?php check_message(); ?>

<?php

$query = 'SELECT *,(
        SELECT COUNT(*)
        FROM class_student
        WHERE class_id = classes.id
    ) AS students
    FROM classes
    ORDER BY year,semester';
$result = mysqli_query($connect, $query);

?>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Students</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>

    <?php while($class = mysqli_fetch_assoc($result)): ?>

        <tr>
            <td><?=$class['id']?></td>
            <td><?=$class['name']?></td>
            <td><?=$class['students']?></td>
            <td><a href="admin-class-list.php?select=<?=$class['id']?>">Select</a></td>
            <td><a href="admin-class-edit.php?select=<?=$class['id']?>">Edit</a></td>
            <td><a href="admin-class-delete.php?select=<?=$class['id']?>">Delete</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<div class="right">

    <a href="admin-class-add.php">Add Class</a>

</div>

<?php

include('includes/footer.php');