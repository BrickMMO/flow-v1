<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Task List');

if(isset($_GET['delete']))
{
    delete_task($_GET['delete']);

    set_message('Task has been deleted!');
    redirect('console-task-list.php');
}

include('includes/header.php');

?>

<h1>Task List</h1>

<?php check_message(); ?>

<?php

$query = 'SELECT *,(
        SELECT COUNT(*)
        FROM class_task
        WHERE task_id = tasks.id
    ) AS classes
    FROM tasks
    ORDER BY name';
$result = mysqli_query($connect, $query);

?>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Classes</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>

    <?php while($task = mysqli_fetch_assoc($result)): ?>

        <tr>
            <td><?=leading_zeros($task['id'])?></td>
            <td>
                <?=$task['name']?>
                <small>
                    <br>
                    <?=$task['description']?>
                    <br>
                    <a href="<?=$task['url']?>"><?=$task['url']?></a>
                </small>
            </td>
            <td><?=$task['classes']?></td>
            <td><a href="console-task-details.php?id=<?=$task['id']?>">&#9782; Details</a></td>
            <td><a href="console-task-assign.php?id=<?=$task['id']?>">&#9755; Assign</a></td>
            <td><a href="console-task-edit.php?id=<?=$task['id']?>">&#10000; Edit</a></td>
            <td><a href="console-task-list.php?delete=<?=$task['id']?>">&#10006; Delete</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<div class="right">

    <a href="console-task-add.php">&#10010; Add Task</a>

</div>

<?php

include('includes/footer.php');