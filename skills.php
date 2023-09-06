<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure();

define('PAGE_TITLE', 'My Skills');

if(isset($_POST['submit']))
{
    
    if($_POST['skills'])
    {

        try 
        {
        
            foreach($_POST['skills'] as $key => $value)
            {
                
                $query = 'INSERT INTO skill_student (
                        skill_id,
                        student_id,
                        rating
                    ) VALUES (
                        "'.$key.'",
                        "'.$_SESSION['student']['id'].'",
                        "'.mysqli_real_escape_string($connect, $value).'"
                    )';
                mysqli_query($connect, $query);

            }

            set_message('Account skills have been edited!', 'success');

        }
        catch(Exception $e) 
        {

            die('here');
            set_message('There was an error editing account skills!', 'error');

        }
        
    }
    else
    {

        set_message('There was an error editing account skills!', 'error');

    }

    redirect('skills.php');

}

$query = 'SELECT skills.*,(
        SELECT rating 
        FROM skill_student
        WHERE skill_id = skills.id
        AND student_id = "'.$_SESSION['student']['id'].'"
        ORDER BY created_at DESC
        LIMIT 1
    ) AS rating
    FROM skills
    ORDER BY name';
$result = mysqli_query($connect, $query);

include('includes/header.php');

?>

<h1>My Skills</h1>

<?php check_message(); ?>

<hr>

<form method="post">

    <input type="hidden" name="submit" value="true">

    <?php while($skill = mysqli_fetch_assoc($result)): ?>

        <label>
            <?=$skill['name']?>
            <br>
            <input type="range" min="1" max="100" value="<?=$skill['rating'] ? $skill['rating'] : 1?>" name="skills[<?=$skill['id']?>]">
        </label>

    <?php endwhile; ?>

    <input type="submit" value="Save">

</form>

<hr>

<div class="right">

    <a href="dashboard.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');