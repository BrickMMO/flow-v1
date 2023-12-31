<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Add Student');

if(isset($_POST['submit']))
{
    
    if($_FILES['import']['type'] == 'text/csv' && $_FILES['import']['error'] == 0)
    {
        
        $file = fopen($_FILES['import']['tmp_name'], 'r');

        while($data = fgetcsv($file, 1000, ",")) 
        {

            if($data[0] != 'First')
            {

                $query = 'SELECT id
                    FROM students
                    WHERE email = "'.$data[2].'"
                    LIMIT 1';
                $result = mysqli_query($connect, $query);

                if(mysqli_num_rows($result))
                {

                    $student = mysqli_fetch_assoc($result);

                    $id = $student['id'];

                }
                else
                {

                    if($data[0] && $data[1] && $data[2])
                    {
                    
                        $query = 'INSERT INTO students (
                                first,
                                last, 
                                email,
                                password
                            ) VALUES (
                                "'.$data[0].'",
                                "'.$data[1].'",
                                "'.$data[2].'",
                                "'.md5('password').'"
                            )';
                        mysqli_query($connect, $query);
                
                        $id = mysqli_insert_id($connect);

                    }

                }

                if(isset($_POST['classes']))
                {

                    foreach($_POST['classes'] as $class_id)
                    {
            
                        student_enroll($id, $class_id);
            
                    }

                }

            }
            
        }

        set_message('Students have been imported!', 'error');

    }
    else
    {

        set_message('There was an error importing this list!', 'error');
        
    }

    redirect('console-student-list.php');

}

include('includes/header.php');

?>

<h1>Import Students</h1>

<?php check_message(); ?>

<hr>

<p>Create a CSV file using the following format:</p>

<table>
    <tr>
        <th>First</th>
        <th>Last</th>
        <th>Email</th>
    </tr>
    <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john.doe@email.com</td>
    </tr>
</table>

<form method="post" enctype="multipart/form-data">

    <input type="hidden" name="submit" value="true">

    <label>
        <input type="file" name="import">
    </label>

    <hr>

    <?php

    $query = 'SELECT *
        FROM classes
        ORDER BY name';
    $result = mysqli_query($connect, $query);

    ?>

    <?php while($class = mysqli_fetch_assoc($result)): ?>

        <label>
            <input type="checkbox" name="classes[]" value="<?=$class['id']?>">
            <?=$class['name']?> - <?=CLASS_SEMESTER[$class['semester']]?> <?=$class['year']?>
        </label>

    <?php endwhile; ?> 

    <input type="submit" value="Import">

</form>

<hr>

<div class="right">

    <a href="console-student-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');