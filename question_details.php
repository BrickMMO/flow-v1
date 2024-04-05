<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

// secure('admin');

define('PAGE_TITLE', 'Answers');

include('includes/header.php');
$student = fetch_student();

?>

<?php
    // Handling form submission for posting answers
    if(isset($_POST['submit']))
    {        
        if($_POST['answer'] && $_POST['question_id'] )
        {    
            try 
            {
                // checking if answer is posted by an admin or a student
                if(isset($_SESSION['admin']['id']))
                {
                    // Construct the SQL query for inserting an answer posted by an admin
                    $query = 'INSERT INTO answers (
                        answer, 
                        question_id,
                        admin_id,
                        created_at
                        ) VALUES ( 
                            "'.mysqli_real_escape_string($connect, $_POST['answer']).'", 
                            "'.mysqli_real_escape_string($connect, $_POST['question_id']).'", 
                            "'.$_SESSION['admin']['id'].'", 
                            current_timestamp()
                        )';    
                    $solved= mysqli_query($connect, $query);
                }
                else
                {
                    // Construct the SQL query for inserting an answer posted by a student
                    $query = 'INSERT INTO answers (
                        answer, 
                        question_id,
                        student_id,
                        created_at
                        ) VALUES ( 
                            "'.mysqli_real_escape_string($connect, $_POST['answer']).'", 
                            "'.mysqli_real_escape_string($connect, $_POST['question_id']).'", 
                            "'.$_SESSION['student']['id'].'", 
                            current_timestamp()
                        )';    
                    $solved= mysqli_query($connect, $query);
                }
                    
            }
            catch(Exception $e) 
            {    
                set_message('There was an error editing this Answer!', 'error');    
            }            
        }
        else
        {
            set_message('There was an error editing this Answer!', 'error');
    
        }
    } 
    // Handling voting on answers
    if (isset($_POST['vote'])) 
    {
        if(isset($_SESSION['admin']['id']))
        {
            // Constructing SQL query to check if admin has already voted on the answer
            $query4 =' SELECT 
                            *
                            FROM votes
                            WHERE admin_id = "'.$_SESSION['admin']['id'].'"
                            AND answer_id = "'.mysqli_real_escape_string($connect, $_POST['answer_id']).'"
                    ';
            // Checking if admin has not voted on the answer
            if(mysqli_num_rows(mysqli_query($connect, $query4))== 0 )
            {
                // Constructing SQL query for inserting vote by admin
                $query3 = 'INSERT INTO votes (
                    answer_id, 
                    admin_id
                    ) VALUES (
                        "'.mysqli_real_escape_string($connect, $_POST['answer_id']).'", 
                        "'.$_SESSION['admin']['id'].'"
                    )';                    
                mysqli_query($connect, $query3); 
            }
            else
            {
                // Constructing SQL query for deleting vote by admin
                $query5 = 'DELETE FROM `votes` 
                            WHERE  admin_id = "'.$_SESSION['admin']['id'].'"
                            AND answer_id = "'.mysqli_real_escape_string($connect, $_POST['answer_id']).'"
                        ';
                mysqli_query($connect ,$query5);
            }
        }
        else
        {          
            // Constructing SQL query to check if student has already voted on the answer
            $query4 =' SELECT 
                            *
                            FROM votes
                            WHERE student_id = "'.$_SESSION['student']['id'].'"
                            AND answer_id = "'.mysqli_real_escape_string($connect, $_POST['answer_id']).'"
                    ';
            //Checking if student has not voted on the answer
            if(mysqli_num_rows(mysqli_query($connect, $query4))== 0 )
            {
                // Constructing SQL query for inserting vote by student
                $query3 = 'INSERT INTO votes (
                    answer_id, 
                    student_id
                    ) VALUES (
                        "'.mysqli_real_escape_string($connect, $_POST['answer_id']).'", 
                        "'.$_SESSION['student']['id'].'"
                    )';                    
                mysqli_query($connect, $query3); 
            }
            else
            {
                // Constructing SQL query for deleting vote by student
                $query5 = 'DELETE FROM `votes` 
                            WHERE  student_id = "'.$_SESSION['student']['id'].'"
                            AND answer_id = "'.mysqli_real_escape_string($connect, $_POST['answer_id']).'"
                        ';
                mysqli_query($connect ,$query5);

            }
        }
                   
           
    }  
?>

<?php
// Fetching the question with associated details
$query1 = 'SELECT 
                q.id as id,
                q.question,
                q.student_id,
                q.admin_id,
                q.created_at,
                CONCAT(s.first, " ", s.last) AS student_name,
                COUNT(a.question_id) AS answers,
                CONCAT(ad.first, " ", ad.last) AS admin_name
            FROM 
                questions q 
            Left JOIN 
                students s ON q.student_id = s.id 
            LEFT JOIN 
                answers a ON a.question_id = q.id 
            LEFT JOIN 
                admins ad ON q.admin_id = ad.id
            WHERE q.id = "'.$_GET['id'].'"';

// Fetching answers related to the question
$query2 = 'SELECT 
                a.id,
                a.answer,
                a.question_id,
                a.student_id,
                a.admin_id,
                a.created_at,
                CONCAT(s.first, " ", s.last) AS student_name,
                COUNT(v.answer_id) AS vote,
                CONCAT(ad.first, " ", ad.last) AS admin_name
            FROM 
                answers AS a
            Left JOIN 
                students AS s ON a.student_id = s.id
            LEFT JOIN 
                votes AS v ON a.id = v.answer_id
            LEFT JOIN 
                admins AS ad ON a.admin_id = ad.id
            WHERE 
                a.question_id = "'.$_GET['id'].'"
            GROUP BY 
                a.id
            ORDER BY 
                vote DESC;' ;

// Execute the queries to get question and answers
$result1 = mysqli_query($connect, $query1);
$result2 =mysqli_query($connect,$query2);
?>
<!-- For preventing resubmission prompt -->
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
<h1>Question</h1> 
<!-- to fetch question -->
<hr>
<?php $task1 = mysqli_fetch_assoc($result1) ?>
    <div class="question-div" >
        <div class= "profile-image" ></div><?php //Not yet Working?>
        <h3>
            <?php 
                if(isset($task1['admin_id']))
                {
                    echo $task1['admin_name'];
                }
                else
                {
                    echo $task1['student_name'];
                }
            ?>
        </h3>
        <div class="date" > 
            <?=$task1['created_at'] ?>
        </div>
        <div class= "question"><b>
            <?=$task1['question'] ?></b>
        </div>
        <div class="" ><!-- Add class when styling  -->
            <?= $task1["answers"]?> Answers 
        </div>
    </div>
<!-- post answer -->
<h2>Post Your Answer</h2>
<form method="post">
    <input type="hidden" name="question_id" value="<?=$task1['id'] ?>">
    <textarea name="answer" rows="14" cols="10" wrap="soft" placeholder="Answer here ........."></textarea>
    <button type="submit" name="submit">Post Answer</button>
</form>
<!-- to fetch answers from student -->

<h2>Answers</h2>
<?php while($task2 = mysqli_fetch_assoc($result2)): ?>
    <?php
    //Checking if the user is an admin or student and setting vote background color accordingly
        if(isset($_SESSION['admin']['id']))
        {
            $query4 =' SELECT 
                        *
                        FROM votes
                        WHERE admin_id = "'.$_SESSION['admin']['id'].'"
                        AND answer_id = "'.$task2['id'].'"
                ';
        } 
        else
        {
            $query4 =' SELECT 
            *
            FROM votes
            WHERE student_id = "'.$_SESSION['student']['id'].'"
            AND answer_id = "'.$task2['id'].'"';
        }
       
        if(mysqli_num_rows(mysqli_query($connect, $query4))== 0 )
        {
            $bgcolor = "transparent";
            $color = "black";
        }
        else
        {
            $bgcolor = "#ff8e00";
            $color = "white";
        }
    ?>
    <div class="question-div" >
        <div class= "profile-image" ></div><?php //Not yet Working?>
        <h3>
            <?php 
                if(isset($task2['admin_id']))
                {
                    echo $task2['admin_name'];
                }
                else
                {
                    echo $task2['student_name'];
                }
            ?>
        </h3>
        <div class="date" > 
            <?=$task2['created_at'] ?>
        </div>
        <div class= "question"><b>
            <?=$task2['answer'] ?></b>
        </div>
        <div class="like" >
            <?= $task2["vote"]?> Votes 
        </div>
        <div class="up_vote">
            <form method="post">
                <input type="hidden" name="answer_id" value="<?=$task2['id'] ?>">
                <button type="submit" name="vote" style="background-color: <?php echo $bgcolor; ?>; color: <?php echo $color; ?>;" >Vote</button>
            </form>
            
        </div>
    </div>
<?php endwhile; ?>


<?php

include('includes/footer.php');
?>