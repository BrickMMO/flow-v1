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
            &#10140; <?=$_SESSION['message']['text']?>
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

