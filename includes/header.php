<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?=PAGE_TITLE?> | Flow</title>

    <link href="styles.css" type="text/css" rel="stylesheet">

</head>
<body>

    <div id="container">        
    

        <?php if(isset($_SESSION['admin'])): ?>

            <?php

            $query = 'SELECT classes.id, classes.name
                FROM admins
                INNER JOIN classes 
                ON classes.id = admins.class_id
                WHERE admins.id = "'.$_SESSION['admin']['id'].'"
                LIMIT 1';
            $result = mysqli_query($connect, $query);

            $class = mysqli_fetch_assoc($result);

            ?>

            Current Class: <a href="admin-class-list.php"><?=$class['name']?></a>

            <hr>

        <?php endif; ?>