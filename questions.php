<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

// secure('admin');

define('PAGE_TITLE', 'Questions');

include('includes/header.php');

?>

<?php

$resultsPerPage =10;

$totalrows = "SELECT * From questions";
$rows= mysqli_query($connect,$totalrows);
$numberOfRows = mysqli_num_rows($rows);

//Determining total number of pages
$numberOfPage= ceil($numberOfRows/$resultsPerPage);

// detemining which page number visitor is currentlt on
if(!isset($_GET['page']))
{
    $page = 1;
}
else
{
    $page = $_GET['page'];
}

// determine the sql LIMIT starting number for the results on the displaying page

$start =($page-1)* $resultsPerPage;



$query = 'SELECT 
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
            GROUP BY 
                q.id 
            ORDER BY 
                q.created_at DESC
            LIMIT '.$start.','.$resultsPerPage.'';

$result = mysqli_query($connect, $query);


?>
<h1>Questions And Answers</h1>

 

<hr>
<div>
    To Ask a Question
    <?php
        if(isset($_SESSION['student']['id']))
        {
            echo '<a href="questions_new.php">Click Here</a>';
        }
        elseif ($_SESSION['admin']['id']) {
            echo '<a href="admin_questions_new.php">Click Here</a>';
        }
    ?>
    

</div>
<?php while($task = mysqli_fetch_assoc($result)): ?>
    <div class="question-div" >
        <div class= "profile-image" ></div><?php //Not yet Working?>
        <h3>
            <?php 
                if(isset($task['admin_id']))
                {
                    echo $task['admin_name'];
                }
                else
                {
                    echo $task['student_name'];
                }
                 ?>
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
    
<?php endwhile; 
$BufferLinks = 2 ?>
<div id="pageLinks">
        <ul>
            <li  <?php if ($page==1) {
                    echo "class='current'";
                }?>> <a href="questions.php?page=1">First</a></li>
            <li>
                <?php if($page > $BufferLinks+1): ?>
                ...
                <?php endif;?>
            </li>
            <?php for ($i=max(1,$page-$BufferLinks); $i <=min($page+$BufferLinks,$numberOfPage) ; $i++):?>
                <li  <?php if ($i==$page) {
                    echo "class='current'";
                }?>>
                <a href="questions.php?page=<?=$i?>"><?=$i?></a></li>
                <?php endfor;?>
            <li>
            <?php if ($page < $numberOfPage - $BufferLinks): ?>
                ... 
                <?php endif;?>
            </li>
            <li <?php if ($page==$numberOfPage) {
                    echo "class='current'";
                }?>> <a href='questions.php?page=<?=$numberOfPage?>'>Last</a></li>
        </ul>
    </div>

<?php

include('includes/footer.php');
?>