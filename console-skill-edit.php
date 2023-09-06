<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Edit Class');

if(isset($_POST['submit']))
{
    
    if($_POST['name'])
    {

        try 
        {
        
            $query = 'UPDATE skills SET 
                name = "'.mysqli_real_escape_string($connect, $_POST['name']).'",
                url = "'.mysqli_real_escape_string($connect, $_POST['url']).'"
                WHERE id = "'.$_GET['id'].'"
                LIMIT 1';
            mysqli_query($connect, $query);

            set_message('Skill has been edited!', 'success');

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
                        WHERE id = "'.$_GET['id'].'"
                        LIMIT 1';    
                    mysqli_query($connect, $query);

                }
                
            }

        }
        catch(Exception $e) 
        {

            set_message('There was an error editing this skill!', 'error');

        }
        
    }
    else
    {

        set_message('There was an error editing this skill!', 'error');

    }

    redirect('console-skill-list.php');

}
elseif(isset($_GET['id']))
{

    $query = 'SELECT *
        FROM skills
        WHERE id = "'.$_GET['id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $skill = mysqli_fetch_assoc($result);
        
    }
    else
    {

        set_message('There was an error loading this skill!', 'error');
        redirect('console-skill-list.php');    

    }

}
else
{

    set_message('There was an error loading this skill!', 'error');
    redirect('console-skill-list.php');

}

include('includes/header.php');

?>

<h1>Edit Skill</h1>

<?php check_message(); ?>

<hr>

<form method="post" enctype="multipart/form-data">

    <input type="hidden" name="submit" value="true">

    <label>
        Name:
        <br>
        <input type="text" name="name" value="<?=$skill['name']?>">
    </label>

    <label>
        Code:
        <br>
        <input type="text" name="url" value="<?=$skill['url']?>">
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