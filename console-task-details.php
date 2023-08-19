<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Task Details');

if(isset($_GET['remove']) && $_GET['type'] == 'task')
{

    task_unassign($_GET['id'], $_GET['remove']);

    set_message('Class has been removed!');
    redirect('console-task-details.php?id='.$_GET['id']);

}
elseif(isset($_GET['id']))
{

    $query = 'SELECT *
        FROM tasks
        WHERE id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $task = mysqli_fetch_assoc($result);

    }
    else
    {

        set_message('There was an error loading this task!', 'error');
        redirect('console-task-list.php');    

    }

}
else
{

    set_message('There was an error loading this task!', 'error');
    redirect('console-task-list.php');
    
}

include('includes/header.php');

?>

<h1>Task Details</h1>

<?php check_message(); ?>

<hr>

<label>
    <small>Name:</small>
    <br>
    <?=$task['name']?>
</label>

<label>
    <small>URL:</small>
    <br>
    <a href="<?=$task['url']?>"><?=$task['url']?></a>
</label>

<label>
    <small>Description:</small>
    <br>
    <?=nl2br($task['description'])?>
</label>

<hr>

<?php 

$query = 'SELECT classes.*,class_task.due_at,(
        SELECT COUNT(*)
        FROM class_student
        WHERE class_id = classes.id
    ) AS students
    FROM classes
    INNER JOIN class_task
    ON classes.id = class_task.class_id
    WHERE task_id = "'.$_GET['id'].'"
    ORDER BY year, semester, name';
$result = mysqli_query($connect, $query);

?>

<h2>Assigned Classes</h2>

<table>
    <tr>
        <th>Class</th>
        <th>Students</th>
        <th></th>
        <th></th>
    </tr>

    <?php while($class = mysqli_fetch_assoc($result)): ?>

        <tr>
            <td>
                <?=$class['code']?> - <?=$class['name']?>
                <small>
                    <br>
                    <?=CLASS_SEMESTER[$class['semester']]?> - <?=$class['year']?>
                    <br>
                    <?=format_date($class['due_at'])?>
                </small>

                <?php

                $query = 'SELECT students.*,student_task.completed_at
                    FROM students
                    INNER JOIN class_student
                    ON class_student.student_id = students.id
                    AND class_student.class_id = "'.$class['id'].'"
                    LEFT JOIN student_task
                    ON student_task.task_id = "'.$_GET['id'].'"
                    AND student_task.student_id = students.id
                    AND student_task.class_id = "'.$class['id'].'"
                    ORDER BY last, first';
                $result2 = mysqli_query($connect, $query);

                ?>

                <table>
                    <tr>
                        <th class="icon"></th>
                        <th>Name</th>
                        <th>Completed</th>
                        <th></th>   
                    </tr>

                    <?php while($student = mysqli_fetch_assoc($result2)): ?>

                        <tr>
                            <td>
                                <?php if($student['github']): ?>
                                    <img src="https://github.com/<?=$student['github']?>.png?size=60" width="60">
                                <?php endif; ?>
                            </td>
                            <td>
                                <?=$student['first']?> <?=$student['last']?>
                            </td>
                            <td>
                                <?php if(isset($student['completed_at'])): ?>
                                    <?=format_date($student['completed_at'])?>
                                <?php endif; ?>
                            </td>
                            <td><a href="console-class-details.php?id=<?=$student['id']?>">&#9782; Details</a></td>
                            
                        </tr>

                    <?php endwhile; ?>

                </table>

            </td>
            <td><?=$class['students']?></td>
            <td><a href="console-class-details.php?id=<?=$class['id']?>">&#9782; Details</a></td>
            <td><a href="console-task-details.php?id=<?=$_GET['id']?>&remove=<?=$class['id']?>&type=class">&#10006; Remove</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<hr>

<div class="right">

    <a href="console-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');