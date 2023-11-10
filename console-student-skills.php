<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Student Details Skill List');

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

<h1>Student Details Skill List</h1>

<?php check_message(); ?>

<hr>

<?php

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


while ($skill = mysqli_fetch_assoc($result_skill)) :
?>

    <label>
        <b><?= $skill['name']  ?> : </b>


        <?php
        $query_student_skill = 'SELECT * FROM `skill_student` WHERE student_id= "' . $_GET['id'] . '" AND skill_id= "' . $skill['id'] . '" ORDER BY created_at;';
        $result_student_skill = mysqli_query($connect, $query_student_skill);

        while ($skill_result = mysqli_fetch_assoc(($result_student_skill))) :
        ?>
            <?= $skill_result['rating'] ?> ,
        <?php endwhile;
        ?>
    </label>
<?php endwhile; ?>