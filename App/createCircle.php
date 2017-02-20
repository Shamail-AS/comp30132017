<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13/02/2017
 * Time: 17:05
 */
require_once('../Models/Circle.php');
require_once('../Models/Circle_Members.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');


use Database\Models\Circle;
use Database\Models\Circle_Members;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;



if (isset($_POST) && !empty($_POST)) {

    $circle = new Circle();
    $circle->circle_name = $_POST['circle_name'];
    $circle->owner = $user->id;
    $circle->save();
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
            <label>Circle name</label>
            <input type="text" name="circle_name" class="form-control" placeholder="Enter circle name">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


</body>
</html>