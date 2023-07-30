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

    /*
    echo '<pre>';
    print_r($response);
    echo '</pre>';
    die();
    /**/

}

