<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Add Class');

if(isset($_POST['submit']))
{
    
    if($_POST['name'])
    {

        try 
        {

            $query = 'INSERT INTO skills (
                    name, 
                    url
                ) VALUES (
                    "'.mysqli_real_escape_string($connect, $_POST['name']).'",
                    "'.mysqli_real_escape_string($connect, $_POST['url']).'"
                )';
            mysqli_query($connect, $query);

            $id = mysqli_insert_id($connect);

            if($_FILES['image'] && $_FILES['image']['error'] == 0)
            {

                $extension = explode('/', $_FILES['image']['type'])[1];

                if(in_array($extension, array('png', 'gif', 'jpg')))
                {

                    $contents = file_get_contents($_FILES['image']['tmp_name']);
                    $contents = base64_encode($contents);
                    $contents = 'data:image/'.$extension.';base64, '.$contents;

                    $query = 'UPDATE skills SET 
                        image = "'.$contents.'"
                        WHERE id = "'.$id.'"
                        LIMIT 1';    
                    mysqli_query($connect, $query);

                }
                
            }

            set_message('Skill has been added!', 'success');

        }
        catch(Exception $e) 
        {

            set_message('There was an error adding this skill!', 'error');

        }
                
    }
    else
    {

        set_message('There was an error adding this skill!', 'error');
        
    }

    redirect('console-skill-list.php');

}

include('includes/header.php');

?>

<h1>Add Skill</h1>

<?php check_message(); ?>

<hr>

<form method="post" enctype="multipart/form-data">

    <input type="hidden" name="submit" value="true">

    <label>
        Name:
        <br>
        <input type="text" name="name">
    </label>

    <label>
        URL:
        <br>
        <input type="text" name="url">
    </label>

    <label>
        Image:
        <br>
        <input type="file" name="image">
    </label>

    <input type="submit" value="Save">

</form>

<hr>

<div class="right">

    <a href="console-skill-list.php">&#10006; Cancel</a>

</div>

<?php

include('includes/footer.php');