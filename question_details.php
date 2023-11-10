<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

// secure('admin');

define('PAGE_TITLE', 'Answers');

include('includes/header.php');

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
                s.id ,
                CONCAT(s.first, " ", s.last) AS name , 
                COUNT(v.answer_id) AS vote  
                FROM answers AS a 
                JOIN students AS s ON a.student_id =s.id 
                LEFT JOIN votes AS v ON a.id= v.answer_id 
                WHERE question_id ="'.$_GET['id'].'" 
                GROUP BY v.answer_id 
                ORDER BY vote DESC' ;

$result1 = mysqli_query($connect, $query1);
$result2 =mysqli_query($connect,$query2);
?>
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
<!-- to fetch answers from student -->

<h2>Answers</h2>
<?php while($task2 = mysqli_fetch_assoc($result2)): ?>
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
    </div>
<?php endwhile; ?>


<?php

include('includes/footer.php');
?>