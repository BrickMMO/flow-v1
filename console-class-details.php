<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Class Details');

if(isset($_GET['remove']))
{

    die();

    task_unassign($_GET['id'], $_GET['remove']);

    set_message('Task has been removed!');
    redirect('console-class-details.php?id='.$_GET['id']);
}
elseif(isset($_GET['id']))
{

    $query = 'SELECT *,(
            SELECT COUNT(*)
            FROM class_student
            WHERE class_id = "'.$_GET['id'].'"
        ) AS students,(
            SELECT COUNT(*)
            FROM class_task
            WHERE class_id = "'.$_GET['id'].'"
        ) AS tasks
        FROM classes
        WHERE id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $record = mysqli_fetch_assoc($result);

    }
    else
    {

        set_message('There was an error loading this class!', 'error');
        redirect('console-class-list.php');    

    }

}
else
{

    set_message('There was an error loading this class!', 'error');
    redirect('console-class-list.php');
    
}

include('includes/header.php');

?>

<h1>Class Details</h1>

<?php check_message(); ?>

<hr>

<label>
    <small>Name:</small>
    <br>
    <?=$record['name']?> - <?=CLASS_SEMESTER[$record['semester']]?> <?=$record['year']?>
</label>

<label>
    <small>Tasks:</small>
    <br>
    <?=$record['tasks']?>
</label>

<label>
    <small>Students:</small>
    <br>
    <?=$record['students']?>
</label>

<hr>

<?php 

$query = 'SELECT tasks.*,class_task.due_at,(
        SELECT COUNT(*)
        FROM student_task
        WHERE student_task.task_id = tasks.id
        AND student_task.class_id = "'.$_GET['id'].'"
    ) AS submitted
    FROM tasks
    INNER JOIN class_task
    ON tasks.id = class_task.task_id
    WHERE class_id = "'.$_GET['id'].'"
    ORDER BY due_at, name';
$result = mysqli_query($connect, $query);

?>

<h2>Assigned Tasks</h2>

<table>
    <tr>
        <th>Class</th>
        <th>Completed</th>
        <th></th>
        <th></th>
    </tr>

    <?php while($task = mysqli_fetch_assoc($result)): ?>

        <tr>
            <td>
                <?=$task['name']?>
                <small>
                    <br>
                    <?=format_date($task['due_at'])?>
                    <br>
                    <a href="<?=$task['url']?>"><?=$task['url']?></a>
                </small>
            </td>
            <td><?=$task['submitted']?>/<?=$record['students']?></td>
            <td><a href="console-task-details.php?id=<?=$task['id']?>">&#9782; Details</a></td>
            <td><a href="console-class-details.php?id=<?=$_GET['id']?>&remove=<?=$task['id']?>">&#10006; Remove</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<hr>

<?php

$query = 'SELECT students.*,(
        SELECT COUNT(*)
        FROM student_task
        WHERE student_task.student_id = students.id
        AND student_task.class_id = "'.$_GET['id'].'"
    ) AS submitted
    FROM students
    INNER JOIN class_student
    ON class_student.student_id = students.id
    AND class_student.class_id = "'.$_GET['id'].'"
    ORDER BY last, first';
$result2 = mysqli_query($connect, $query);

?>

<h2>Enrolled Students</h2>

<table>
    <tr>
        <th class="icon"></th>
        <th>Name</th>
        <th>Completed</th>
        <th></th>   
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
                <small>
                    <br>
                    <a href="mailto:<?=$student['email']?>"><?=$student['email']?></a>
                    <?php if($student['github']): ?>
                        <br>
                        <a href="https://github.com/<?=$student['github']?>/">https://github.com/<?=$student['github']?>/</a>
                    <?php endif; ?>
                    <?php if($student['linkedin']): ?>
                        <br>
                        <a href="https://www.linkedin.com/in/<?=$student['linkedin']?>/">https://www.linkedin.com/in/<?=$student['linkedin']?>/</a>
                    <?php endif; ?>
                </small>
            </td>
            <td><?=$student['submitted']?>/<?=$record['tasks']?></td>
            <td><a href="console-student-details.php?id=<?=$student['id']?>">&#9782; Details</a></td>
            <td><a href="console-class-details.php?id=<?=$student['id']?>">&#9782; Remove</a></td>
            
        </tr>

    <?php endwhile; ?>

</table>

<hr>

<div class="right">

    <a href="console-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');