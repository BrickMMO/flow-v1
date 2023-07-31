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

        <div class="center">

            <span style="font-size: 90px;">&#8660;</span>

        </div>

        <hr>

        <?php if(isset($_SESSION['admin'])): ?>

            <?php

            $query = 'SELECT id, name
                FROM classes
                WHERE id = "'.$_SESSION['admin']['class_id'].'"
                LIMIT 1';
            $result = mysqli_query($connect, $query);

            $class = mysqli_fetch_assoc($result);

            ?>

            Current Class: <a href="console-class-list.php"><?=$class['name']?></a>

            <hr>

        <?php endif; ?>