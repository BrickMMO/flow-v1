<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

// secure('admin');

define('PAGE_TITLE', 'Questions');

include('includes/header.php');

?>

<?php

$query = "SELECT 
            q.id as id ,
            q.question,
            q.student_id,
            q.created_at ,
            CONCAT(s.first, ' ', s.last) AS name ,
            COUNT(a.question_id) AS answers 
            FROM questions q 
            JOIN students s ON q.student_id = s.id 
            LEFT JOIN answers AS a ON a.question_id = q.id 
            GROUP BY q.id 
            ORDER BY created_at DESC";
$result = mysqli_query($connect, $query);


?>
<h1>Questions And Answers</h1>

 

<hr>
<?php while($task = mysqli_fetch_assoc($result)): ?>
    <div class="question-div" >
        <div class= "profile-image" ></div><?php //Not yet Working?>
        <h3>
            <?=$task['name'] ?>
        </h3>
        <div class="date" > 
            <?=$task['created_at'] ?>
        </div>
        <div class= "question">
            <?=$task['question'] ?>
        </div>
        <div class="" >
            <?= $task["answers"]?> Answers 
        </div>
        <div class="show-answer"> 
            <a href="question_details.php?id=<?=$task['id']?>">Show Answers</a>
        </div>
    </div>
<?php endwhile; ?>


<?php

include('includes/footer.php');
?>