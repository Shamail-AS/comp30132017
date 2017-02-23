<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22/02/2017
 * Time: 14:28
 */
require_once('../Models/Circle.php');
require_once('../Models/Circles_Member.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');

use Database\Models\Circle;
use Http\Session\SessionManager;
use Database\Models\Circles_Member;
use Database\Models\User;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;


if (!isset($_GET['id'])) {

    $session->redirect('home');
}

$circle_id = $_GET['id'];


$circle = new Circle();
$name = $circle->idToName($circle_id);
$group = new Circles_Member();
$members = $group->getByCircleId($circle_id);


if (isset($_POST) && !empty($_POST)) {
    $friend_name = $_POST['user'];
    if($user->isRegistered($friend_name) !=0) {
        $session->addError('noUser', 'This user does not exist');
        $session->redirect("#");
    }
    else {

        $user = new User();
        $id2 = $user->getIdbyName($friend_name);

        if ($group->areConnected($id2, $circle_id) == true) {
            $session->addError('userExists', 'This user is  already in a circle');

        } else {
            $group->user = $id2;
            $group->circle = $circle_id;
            $group->save();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Circle</title>
    <?php include('common/nav.php') ?>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
    <?php
        echo "<div align = center ><h2> $name</h2> 
        </div>"
        ?>
<body>
    <h3>List of members</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($members as $m) { ?>
            <tr>
                <td><?php echo $user->getNameById($m->user) ?></td>
                <td><a href="" class="btn btn-sm btn-primary">Profile</a></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

    <h3>Add friends to the group</h3>
    <form action="#" method="post">
        <div class="form-group">
            <?php if ($session->hasErrors() && $session->getError("noUser")) {
                $error_msg = $session->getError("noUser");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <?php if ($session->hasErrors() && $session->getError("userExists")) {
                $error_msg = $session->getError("userExists");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <input type="text" name="user" class="form-control" placeholder="Enter friends username">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <p><h4><a href = listCircle.php>Back to my Circles</h4>

</body>
</html>

