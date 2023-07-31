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

    $_SESSION['admin']['class_id'] = $_GET['select'];

    set_message('Class has been changed!');
    redirect('admin-class-list.php');
}

elseif(isset($_GET['delete']))
{
    $query = 'DELETE FROM classes
        WHERE id = "'.$_GET['delete'].'"
        LIMIT 1';
    mysqli_query($connect, $query);

    $query = 'UPDATE admins SET
        class_id = (
            SELECT id
            FROM classes
            ORDER BY year DESC
            LIMIT 1
        ) 
        WHERE class_id NOT IN (
            SELECT id 
            FROM classes
        )';
    mysqli_query($connect, $query);

    set_message('Class has been deleted!');
    redirect('admin-class-list.php');
}

define('PAGE_TITLE', 'DasChange Classhboard');

include('includes/header.php');

?>

<h1>Class List</h1>

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
            <td>
                <a href="admin-class-list.php?select=<?=$class['id']?>">
                    <?php if($class['id'] == $_SESSION['admin']['class_id']): ?>
                        &#9745;
                    <?php else: ?>
                        &#9744; 
                    <?php endif; ?>
                    Select
                </a>
            </td>
            <td><a href="admin-class-edit.php?id=<?=$class['id']?>">&#10000; Edit</a></td>
            <td><a href="admin-class-list.php?delete=<?=$class['id']?>">&#10006; Delete</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<div class="right">

    <a href="admin-class-add.php">&#10010; Add Class</a>

</div>

<?php

include('includes/footer.php');