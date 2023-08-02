<?php

function set_message($text, $type = 'error')
{
    $_SESSION['message']['text'] = $text;
    $_SESSION['message']['type'] = $type;
}

function check_message()
{
    if(isset($_SESSION['message']))
    {

        ?>  

        <div class="<?=$_SESSION['message']['type']?>">
            &#10132; <?=$_SESSION['message']['text']?>
        </div>

        <?php

        unset( $_SESSION['message']);

    }
}

function redirect($page)
{
    header('Location: '.$page);
    die();
}

function secure($type = 'student')
{
    if(!isset($_SESSION[$type]))
    {
        set_message('You must be logged in to view this page!', 'error');
        redirect('/');
    }
}

function sendgrid_mail($to_email, $to_name, $subject, $message)
{

    $params = array(
        'to'        => $to_email,
        'toname'    => $to_name,
        'from'      => "brickmmo@gmail.com",
        'fromname'  => "BrickMMO",
        'subject'   => $subject,
        'text'      => $message,
    );

    $request = 'https://api.sendgrid.com/api/mail.send.json';

    $session = curl_init($request);

    curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . SENDGRID_API_KEY));
    curl_setopt ($session, CURLOPT_POST, true);
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($session);
    curl_close($session);

}

function select($name, $options, $selected = false)
{
 
    ?>

    <select name="<?=$name?>">

        <?php foreach($options as $value => $option): ?>
            <option value="<?=$value?>"><?=$option?></option>
        <?php endforeach; ?>

    </select>

    <?php

}

function delete_class($id)
{

    global $connect;

    $query = 'DELETE FROM class_task
        WHERE class_id = "'.$id.'"';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM class_student
        WHERE class_id = "'.$id.'"';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM classes
        WHERE id = "'.$id.'"
        LIMIT 1';
    mysqli_query($connect, $query);

}

function delete_task($id)
{

    global $connect;

    $query = 'DELETE FROM student_task
        WHERE task_id = "'.$id.'"';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM class_task
        WHERE task_id = "'.$id.'"';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM tasks
        WHERE id = "'.$id.'"
        LIMIT 1';
    mysqli_query($connect, $query);

}

function delete_student($id)
{

    global $connect;

    $query = 'DELETE FROM student_task
        WHERE student_id = "'.$id.'"';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM class_student
        WHERE student_id = "'.$id.'"';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM students
        WHERE id = "'.$id.'"
        LIMIT 1';
    mysqli_query($connect, $query);

}

function student_enroll($student_id, $class_id)
{

    global $connect;

    $query = 'INSERT IGNORE INTO class_student (
            class_id,
            student_id
        ) VALUES (
            "'.$class_id.'",
            "'.$student_id.'"
        )';
    mysqli_query($connect, $query);

}

function task_assign($task_id, $class_id)
{

    global $connect;

    $query = 'INSERT IGNORE INTO class_task (
            class_id,
            task_id
        ) VALUES (
            "'.$class_id.'",
            "'.$task_id.'"
        )';
    mysqli_query($connect, $query);

}

function task_unassign($task_id, $class_id)
{

    global $connect;

    $query = 'DELETE FROM class_task
        WHERE class_id = "'.$class_id.'"
        AND task_id = "'.$task_id.'"';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM student_task
        WHERE class_id = "'.$class_id.'"
        AND task_id = "'.$task_id.'"';
    mysqli_query($connect, $query);

}
