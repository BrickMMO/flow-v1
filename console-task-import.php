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

                $query = 'INSERT INTO tasks (
                        name,
                        description, 
                        url
                    ) VALUES (
                        "'.$data[0].'",
                        "'.$data[1].'",
                        "'.$data[2].'"
                    )';
                mysqli_query($connect, $query);
        
                $id = mysqli_insert_id($connect);


                if(isset($_POST['classes']))
                {

                    foreach($_POST['classes'] as $class_id)
                    {
            
                        task_assign($id, $class_id, $data[3]);
            
                    }

                }

            }
            
        }

        set_message('Tasks have been imported!', 'error');

    }
    else
    {

        set_message('There was an error importing this list!', 'error');
        
    }

    redirect('console-tasks-list.php');

}

include('includes/header.php');

?>

<h1>Import Tasks</h1>

<?php check_message(); ?>

<hr>

<p>Create a CSV file using the following format:</p>

<table>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>URL</th>
        <th>Due Date</th>
    </tr>
    <tr>
        <td>Accounts</td>
        <td>Create accounts for GitHub, Stack Overflow, and Discord.</td>
        <td>https://tasks.brickmmo.com/accounts</td>
        <td>2023-09-15</td>
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