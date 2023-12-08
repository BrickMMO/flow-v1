<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

// secure('admin');

define('PAGE_TITLE', 'Answers');

include('includes/header.php');
$student = fetch_student();
$solved = FALSE;
?>
<?php
    
    if(isset($_POST['submit']))
    {     
    
        if($_POST['question'] )
        {    
            
            try 
            {
                
                $query = 'INSERT INTO questions (
                    id,
                    question, 
                    student_id, 
                    created_at
                    )  VALUES ( 
                        "",
                        "'.mysqli_real_escape_string($connect, $_POST['question']).'",  
                        "'.$_SESSION['student']['id'].'", 
                        current_timestamp()
                    )';    
            $solved = mysqli_query($connect, $query);    
            }
            catch(Exception $e) 
            {    
                set_message('There was an error asking the question!', 'error');    
            }            
        }
        else
        {
            set_message('There was an error asking this Question!', 'error');
    
        }
    }  
    if ($solved == true)
    {
        redirect("questions.php");
    }
       
?>
        
    




<h2>Ask Your Question</h2>
<form method="post">
    <textarea name="question" rows="14" cols="10" wrap="soft" placeholder="Ask Question here ........."></textarea>
    <button type="submit" name="submit">Ask Question</button>
</form>

<?php

include('includes/footer.php');
?>