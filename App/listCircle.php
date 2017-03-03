<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22/02/2017
 * Time: 14:13
 */
require_once('../Models/Circle.php');
require_once('../Models/Circles_Member.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');



use Database\Models\Circle;
use Http\Session\SessionManager;
use Database\Models\Circles_Member;


$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;
$user_id = $user->id;




$circle = new Circle();
$circles = new Circles_Member();
$mycircles = $circles->getByUser($user_id);







?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">



    <title>Your Circles</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <div align = center><h1>Circles</h1></div>
</head>
<body>
<div class = container>
<ul>
<?php include('common/nav.php') ?>
<ul class="list-group"></ul>
<?php
if (!empty($mycircles)) {
    foreach ($mycircles as $a) {
        if($circle->ownerById($a->circle) == $user->id){
            $myname = $circle->idToName($a->circle) . " (Your Circle)";
        }
        else {
            $myname = $circle->idToName($a->circle);
        }
        echo "<div align = center><a href=\"viewCircle.php?id=" . $a->circle . "\"><li class=\"list-group-item\">$myname</li></a>";
    }
}
else {
    echo "You have no circles";
}
?>
</ul>
</div>

<div align = center><a href = "createCircle.php"><button class="btn btn-primary">Create New Circle</button></a></div>
</div>





</body>
</html>

