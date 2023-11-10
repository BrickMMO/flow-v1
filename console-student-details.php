<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Student Details');

if (isset($_GET['remove'])) {

    die();

    task_unassign($_GET['id'], $_GET['remove']);

    set_message('Task has been removed!');
    redirect('console-class-details.php?id=' . $_GET['id']);
} elseif (isset($_GET['id'])) {

    $query = 'SELECT *,(
            SELECT COUNT(*)
            FROM class_student
            WHERE student_id = "' . $_GET['id'] . '"
        ) AS classes
        FROM students
        WHERE id = "' . $_GET['id'] . '"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result)) {

        $student = mysqli_fetch_assoc($result);
    } else {

        set_message('There was an error loading this student!', 'error');
        redirect('console-class-list.php');
    }
} else {

    set_message('There was an error loading this student!', 'error');
    redirect('console-class-list.php');
}

include('includes/header.php');

?>

<h1>Student Details</h1>

<?php check_message(); ?>

<hr>

<label>
    <small>Name:</small>
    <br>
    <?= $student['first'] ?> <?= $student['last'] ?>
</label>

<label>
    <small>Email:</small>
    <br>
    <a href="mailto:<?= $student['email'] ?>"><?= $student['email'] ?></a>
</label>

<?php if ($student['github']) : ?>
    <label>
        <small>GitHub:</small>
        <br>
        <a href="https://github.com/<?= $student['github'] ?>/">https://github.com/<?= $student['github'] ?>/</a>
    </label>
<?php endif; ?>

<?php if ($student['linkedin']) : ?>
    <label>
        <small>LinkedIn:</small>
        <br>
        <a href="https://www.linkedin.com/in/<?= $student['linkedin'] ?>/">https://www.linkedin.com/in/<?= $student['linkedin'] ?>/</a>
    </label>
<?php endif; ?>

<label>
    <small>Classes:</small>
    <br>
    <?= $student['classes'] ?>
</label>

<?php

$query = 'SELECT classes.*,(
        SELECT COUNT(*)
        FROM class_task
        WHERE class_id = classes.id
    ) AS tasks
    FROM classes
    INNER JOIN class_student
    ON classes.id = class_student.class_id
    WHERE student_id = "' . $_GET['id'] . '"
    ORDER BY year, semester, name';
$result = mysqli_query($connect, $query);

$query_skill = 'SELECT skills.*,(
    SELECT rating 
    FROM skill_student
    WHERE skill_id = skills.id
    AND student_id = "' . $_GET['id'] . '"
    ORDER BY created_at DESC
    LIMIT 1
) AS rating
FROM skills
ORDER BY name';

$result_skill = mysqli_query($connect, $query_skill);

?>

<label>
    <small>Skills:</small>
    <br>
    <?php while ($skill = mysqli_fetch_assoc($result_skill)) : ?>

        <label>
            <?= $skill['name'] ?>
            <br>
            <input type="range" disabled min="1" max="100" value="<?= $skill['rating'] ? $skill['rating'] : 1 ?>" name="skills[<?= $skill['id'] ?>]">
        </label>

    <?php endwhile; ?>
</label>


<a href="console-student-skills.php?id=<?= $_GET['id'] ?>">More about skill list</a>

<hr>

<h2>Enrolled Classes</h2>

<table>
    <tr>
        <th>Class</th>
        <th>Tasks</th>
        <th></th>
        <th></th>
    </tr>

    <?php while ($class = mysqli_fetch_assoc($result)) : ?>

        <tr>
            <td>
                <?= $class['code'] ?> - <?= $class['name'] ?>
                <small>
                    <br>
                    <?= CLASS_SEMESTER[$class['semester']] ?> - <?= $class['year'] ?>
                </small>

                <?php

                $query = 'SELECT tasks.*,class_task.due_at,student_task.completed_at
                    FROM tasks
                    INNER JOIN class_task
                    ON class_task.task_id = tasks.id
                    LEFT JOIN student_task
                    ON student_task.task_id = tasks.id
                    AND student_task.student_id = "' . $_GET['id'] . '"
                    AND student_task.class_id = "' . $class['id'] . '"
                    WHERE class_task.class_id = "' . $class['id'] . '"
                    ORDER BY due_at ASC';
                $result2 = mysqli_query($connect, $query);

                ?>

                <table>
                    <tr>
                        <th>Name</th>
                        <th>Due</th>
                        <th>Completed</th>
                        <th></th>
                    </tr>

                    <?php while ($task = mysqli_fetch_assoc($result2)) : ?>

                        <tr>
                            <td>
                                <?= $task['name'] ?>
                                <small>
                                    <br>
                                    <a href="<?= $task['url'] ?>"><?= $task['url'] ?></a>
                                </small>
                            </td>
                            <td><?= format_date($task['due_at']) ?></td>
                            <td>
                                <?php if ($task['completed_at']) : ?>
                                    <?= format_date($task['completed_at']) ?>
                                <?php endif; ?>
                            </td>
                            <td><a href="console-task-details.php?id=<?= $task['id'] ?>">&#9782; Details</a></td>
                        </tr>

                    <?php endwhile; ?>

                </table>

            </td>
            <td><?= $class['tasks'] ?></td>
            <td><a href="console-class-details.php?id=<?= $class['id'] ?>">&#9782; Details</a></td>
            <td><a href="console-student-details.php?id=<?= $_GET['id'] ?>&remove=<?= $class['id'] ?>&type=class">&#10006; Remove</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<hr>

<div class="right">

    <a href="console-task-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');
