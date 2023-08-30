<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Class Details');

if(isset($_GET['remove']) && $_GET['type'] == 'task')
{

    task_unassign($_GET['remove'], $_GET['id']);

    set_message('Task has been removed!');
    redirect('console-class-details.php?id='.$_GET['id']);
}
if(isset($_GET['remove']) && $_GET['type'] == 'student')
{

    student_unenroll($_GET['remove'], $_GET['id']);

    set_message('Student has been removed!');
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

        $class = mysqli_fetch_assoc($result);

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
    <?=$class['name']?> - <?=CLASS_SEMESTER[$class['semester']]?> <?=$class['year']?>
</label>

<label>
    <small>Tasks:</small>
    <br>
    <?=$class['tasks']?>
</label>

<label>
    <small>Students:</small>
    <br>
    <?=$class['students']?>
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
    ORDER BY due_at ASC, name';
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
            <td><?=$task['submitted']?>/<?=$class['students']?></td>
            <td><a href="console-task-details.php?id=<?=$task['id']?>">&#9782; Details</a></td>
            <td><a href="console-class-details.php?id=<?=$_GET['id']?>&remove=<?=$task['id']?>&type=task">&#10006; Remove</a></td>
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
$result = mysqli_query($connect, $query);

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

    <?php while($student = mysqli_fetch_assoc($result)): ?>

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
            <td><?=$student['submitted']?>/<?=$class['tasks']?></td>
            <td><a href="console-student-details.php?id=<?=$student['id']?>">&#9782; Details</a></td>
            <td><a href="console-class-details.php?id=<?=$_GET['id']?>&remove=<?=$student['id']?>&type=student">&#9782; Remove</a></td>
            
        </tr>

    <?php endwhile; ?>

</table>

<hr>

<div class="right">

    <a href="console-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');