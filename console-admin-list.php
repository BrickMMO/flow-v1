<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

secure('admin');

define('PAGE_TITLE', 'Admin List');

if(isset($_GET['delete']))
{
    $query = 'DELETE FROM admins
        WHERE id = "'.$_GET['delete'].'"
        AND id != "'.$_SESSION['admin']['id'].'"
        LIMIT 1';
    mysqli_query($connect, $query);

    set_message('Admin has been deleted!');
    redirect('console-admin-list.php');
}

include('includes/header.php');

?>

<h1>Admin List</h1>

<?php check_message(); ?>

<?php

$query = 'SELECT *
    FROM admins
    ORDER BY last, first';
$result = mysqli_query($connect, $query);

?>

<table>
    <tr>
        <th></th>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th></th>
        <th></th>
    </tr>

    <?php while($admin = mysqli_fetch_assoc($result)): ?>

        <tr>
            <td>
                <?php if($admin['github']): ?>
                    <img src="https://github.com/<?=$admin['github']?>.png?size=60" width="60">
                <?php endif; ?>
            </td>
            <td><?=$admin['id']?></td>
            <td>
                <?=$admin['first']?> <?=$admin['last']?>
                <small>
                    <?php if($admin['github']): ?>
                        <br>
                        <a href="https://github.com/<?=$admin['github']?>/">https://github.com/<?=$admin['github']?>/</a>
                    <?php endif; ?>
                </small>
            </td>
            <td><a href="mailto:<?=$admin['email']?>"><?=$admin['email']?></a></td>
            <td><a href="console-admin-edit.php?id=<?=$admin['id']?>">&#10000; Edit</a></td>
            <td>
                <?php if($_SESSION['admin']['id'] != $admin['id']): ?>
                    <a href="console-admin-list.php?delete=<?=$admin['id']?>">&#10006; Delete</a>
                <?php endif; ?>
            </td>
        </tr>

    <?php endwhile; ?>

</table>

<div class="right">

    <a href="console-admin-add.php">&#10010; Add Admin</a>

</div>

<?php

include('includes/footer.php');