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
    
    if(isset($_POST['submit']))
    {        
        if($_POST['answer'] && $_POST['question_id'] )
        {    
            try 
            {
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
    if (isset($_POST['vote'])) 
    {

        $query4 =' SELECT 
                        *
                        FROM votes
                        WHERE student_id = "'.$_SESSION['student']['id'].'"
                        AND answer_id = "'.mysqli_real_escape_string($connect, $_POST['answer_id']).'"
                ';
        if(mysqli_num_rows(mysqli_query($connect, $query4))== 0 )
        {
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
            $query5 = 'DELETE FROM `votes` 
                        WHERE  student_id = "'.$_SESSION['student']['id'].'"
                        AND answer_id = "'.mysqli_real_escape_string($connect, $_POST['answer_id']).'"
                    ';
            mysqli_query($connect ,$query5);

        }
        
                   
           
    }  
?>

<?php

$query1 = 'SELECT 
                q.id as id ,
                q.question,
                q.student_id,
                q.created_at ,
                CONCAT(s.first, " ", s.last) AS name ,
                COUNT(a.question_id) AS answers 
                FROM questions q 
                JOIN students s ON q.student_id = s.id 
                LEFT JOIN answers AS a ON a.question_id = q.id 
                WHERE q.id = "'.$_GET['id'].'"';

$query2 = 'SELECT 
                a.id ,
                a.answer , 
                a.question_id ,
                a.student_id,
                a.created_at ,
                CONCAT(s.first, " ", s.last) AS name , 
                COUNT(v.answer_id) AS vote  
                FROM answers AS a 
                JOIN students AS s ON a.student_id =s.id 
                Left JOIN votes AS v ON a.id= v.answer_id 
                WHERE question_id ="'.$_GET['id'].'" 
                GROUP BY a.id 
                ORDER BY vote DESC' ;

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
            <?=$task1['name'] ?>
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
    $query4 =' SELECT 
                        *
                        FROM votes
                        WHERE student_id = "'.$_SESSION['student']['id'].'"
                        AND answer_id = "'.$task2['id'].'"
                ';
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
            <?=$task2['name'] ?>
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