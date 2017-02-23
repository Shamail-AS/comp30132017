<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13/02/2017
 * Time: 17:05
 */
require_once('../Models/Circle.php');
require_once('../Models/Circles_Member.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');



use Database\Models\Circle;
use Database\Models\Circles_Member;
use Http\Session\SessionManager;
use Database\Models\Users;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;



if (isset($_POST) && !empty($_POST)) {

    $circle = new Circle();
    $circle->circle_name = $_POST['circle_name'];
    $circle->owner = $user->id;
    $circles_member = new Circles_Member();
    $circles_member->circle = $circle->id;
    $circles_member->user = $circle->owner;

    if ($circle->isExisted()) {
        $session->addError('circleExisted', 'Please choose a different name');
        $session->redirect('createCircle');

    } else {
        $circle->save();
        $circles_member = new Circles_Member();
        $circles_member->circle = $circle->id;
        $circles_member->user = $circle->owner;
        $circles_member->save();
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

    <title>Fixed top navbar example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <h1>Create Circle</h1>
    <form action="createCircle.php" method="post">
        <div class="form-group">
            <?php if ($session->hasErrors() && $session->getError("circleExisted")) {
            $error_msg = $session->getError("circleExisted");
            ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <input type="text" name="circle_name" class="form-control" placeholder="Enter circle name">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <p><h4><a href = listCircle.php>Back to my Circles</h4></p>
    </form>
</div>


</body>
</html>