<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Student List');

if(isset($_GET['delete']))
{
    delete_student($_GET['delete']);

    set_message('Student has been deleted!');
    redirect('console-student-list.php');
}

include('includes/header.php');

?>

<h1>Student List</h1>

<?php check_message(); ?>

<?php

$query = 'SELECT *,(
        SELECT COUNT(*)
        FROM class_student
        WHERE student_id = students.id
    ) AS classes
    FROM students
    ORDER BY last, first';
$result = mysqli_query($connect, $query);

?>

<table>
    <tr>
        <th></th>
        <th>ID</th>
        <th>Name</th>
        <th>Classes</th>
        <th></th>
        <th></th>
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
            <td><?=$student['id']?></td>
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
            <td><?=$student['classes']?></td>
            <td><a href="console-student-details.php?id=<?=$student['id']?>">&#9782; Details</a></td>
            <td><a href="console-student-enroll.php?id=<?=$student['id']?>">&#9755; Enroll</a></td>
            <td><a href="console-student-edit.php?id=<?=$student['id']?>">&#10000; Edit</a></td>
            <td><a href="console-student-list.php?delete=<?=$student['id']?>">&#10006; Delete</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<div class="right">

    <a href="console-student-import.php">&#9776; Import Students</a>
    <a href="console-student-add.php">&#10010; Add Student</a>

</div>

<?php

include('includes/footer.php');