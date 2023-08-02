<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

if(isset($_GET['select']))
{

    update_session_class($_GET['select']);

    set_message('Class has been changed!', 'success');
    redirect('console-class-list.php');
    
}

elseif(isset($_GET['delete']))
{

    delete_class($_GET['delete']);

    update_session_class();

    set_message('Class has been deleted!');
    redirect('console-class-list.php');

}

define('PAGE_TITLE', 'Class List');

include('includes/header.php');

?>

<h1>Class List</h1>

<?php check_message(); ?>

<?php

$query = 'SELECT *,(
        SELECT COUNT(*)
        FROM class_student
        WHERE class_id = classes.id
    ) AS students,(
        SELECT COUNT(*)
        FROM class_task
        WHERE class_id = classes.id
    ) AS tasks
    FROM classes
    ORDER BY year, semester, name';
$result = mysqli_query($connect, $query);

?>

<table>
    <tr>
        <th>ID</th>
        <th>Class</th>
        <th>Tasks</th>
        <th>Students</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>

    <?php while($class = mysqli_fetch_assoc($result)): ?>

        <tr>
            <td><?=$class['id']?></td>
            <td>
                <?=$class['code']?> - <?=$class['name']?>
                <small>
                    <br>
                    <?=CLASS_SEMESTER[$class['semester']]?> - <?=$class['year']?>
                </small>
            </td>
            <td><?=$class['tasks']?></td>
            <td><?=$class['students']?></td>
            <td><a href="console-class-details.php?id=<?=$class['id']?>">&#9782; Details</a></td>
            <td><a href="console-class-edit.php?id=<?=$class['id']?>">&#10000; Edit</a></td>
            <td><a href="console-class-list.php?delete=<?=$class['id']?>">&#10006; Delete</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<div class="right">

    <a href="console-class-add.php">&#10010; Add Class</a>

</div>

<?php

include('includes/footer.php');