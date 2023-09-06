<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Class List');

if(isset($_GET['delete']))
{

    delete_skill($_GET['delete']);

    set_message('Skill has been deleted!');
    redirect('console-skill-list.php');

}

include('includes/header.php');

?>

<h1>Skill List</h1>

<?php check_message(); ?>

<?php

$query = 'SELECT *
    FROM skills
    ORDER BY name';
$result = mysqli_query($connect, $query);

?>

<table>
    <tr>
        <th>ID</th>
        <th></th>
        <th>Name</th>
        <th>URL</th>
        <th></th>
        <th></th>
    </tr>

    <?php while($class = mysqli_fetch_assoc($result)): ?>

        <tr>
            <td><?=leading_zeros($class['id'])?></td>
            <td>IMAGE</td>
            <td>
                <?=$class['name']?>
            </td>
            <td><a href="<?=$class['url']?>"><?=$class['url']?></a></td>
            <td><a href="console-skill-edit.php?id=<?=$class['id']?>">&#10000; Edit</a></td>
            <td><a href="console-skill-list.php?delete=<?=$class['id']?>">&#10006; Delete</a></td>
        </tr>

    <?php endwhile; ?>

</table>

<div class="right">

    <a href="console-skill-add.php">&#10010; Add Skill</a>

</div>

<?php

include('includes/footer.php');