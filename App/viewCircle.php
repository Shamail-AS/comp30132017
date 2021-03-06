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
require_once('../Models/Friendship.php');
require_once('../Models/Messages.php');


use Database\Models\Circle;
use Http\Session\SessionManager;
use Database\Models\Circles_Member;
use Database\Models\Messages;
use Database\Models\Friendship;

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
$chat = new Messages();
$msgs = $chat->getCircleMSG($circle_id);


if ($group->areConnected($user->id, $circle_id) == False) {
    $session->redirect('home');
}


if ($session->user->id == $circle->ownerById($circle_id)) {
    $isViewingOwn = true;
} else {
    $isViewingOwn = false;
}


if (isset($_POST) && !empty($_POST) && !isset($_POST["delete_admin"]) && !isset($_POST["disband_group"])
    && !isset($_POST["leave_group"]) && !isset($_POST["group_chat"])
) {
    $friend_name = $_POST['user'];
    if (!$user->isRegistered($friend_name)) {
        $session->addError('noUser', 'This user does not exist');
        $session->redirect("#");

    } else {
        $friendships = new Friendship();
        $reciever = $user->getIdbyName($friend_name);
        $reciever_object = $user->getUserById($reciever);


        if (!$friendships->areFriends($user, $reciever_object)) {
            $session->addError('notFriends', 'You can only add your friends to the group');
            $session->redirect("#");

        } else if ($group->areConnected($reciever, $circle_id) == true) {
            $session->addError('userExists', 'This user is  already in a circle');
            $session->redirect("#");
        } else {
            $group->user = $reciever;
            $group->circle = $circle_id;
            $group->save();
            $session->redirect("#");
        }
    }
} else if (isset($_POST["delete_admin"]) && !empty($_POST["delete_admin"])) {

    $username = $_POST["delete_admin"];
    if ($username == $user->id) {
        echo '<script language="javascript">';
        echo 'alert("Please disband your group if you are intending to leave it.")';
        echo '</script>';
    } else {

        $group->delete("user = $username  AND circle = $circle_id");
        $session->redirect("#");
    }
} else if (isset($_POST["disband_group"]) && !empty($_POST["disband_group"])) {

    $chat->delete("circle = $circle_id");
    foreach ($members as $m) {
        $group->delete("user = $m->user  AND circle = $circle_id");
    }

    $circle->delete("id = $circle_id");

    $session->redirect("listCircle.php");


} else if (isset($_POST["leave_group"]) && !empty($_POST["leave_group"])) {

    $group->delete("user = $user->id  AND circle = $circle_id");
    $session->redirect("listCircle.php");

} else if (isset($_POST["group_chat"]) && !empty($_POST["group_chat"])) {

    $msg = $_POST['group_chat'];
    $chat->message = $msg;
    $chat->circle = $circle_id;
    $chat->from_user = $user->id;
    $chat->save();
    $session->redirect("#");


}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Circle</title>
    <?php include('common/nav.php') ?>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php
?>
<body>
<?php echo "<div align = center ><h2> $name</h2> </div>" ?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div style="align:right; float:right; position: relative; display:inline">
                <?php
                $createLink = "createAlbumCircle.php?id=" . $circle_id;
                echo "<a href='" . $createLink . "''
           class=\"btn btn-sm btn-primary\">Create Album</a>";
                ?>
                <?php if ($isViewingOwn) { ?>
                    <form class="form-outline" method="post" action="#" name="disband_group"
                          style="display:inline-block">
                        <input type="hidden" name="disband_group" value="disband_group">
                        <button class="btn btn-sm btn-primary" type="submit">Disband Group</button>
                    </form>
                <?php } else { ?>
                    <form class="form-outline" method="post" action="#" name="leave_group" style="display:inline-block">
                        <input type="hidden" name="leave_group" value="leave_group">
                        <button class="btn btn-sm btn-danger" type="submit">Leave Group</button>
                    </form>
                <?php } ?>
            </div>


            <h5>Group Chat</h5>
            <div class="container">
                <div class="card-container" style="max-height: 60vh; overflow: auto">
                    <?php if ($msgs != NULL) {
                        foreach ($msgs as $m) { ?>
                            <div class="card">
                                <div class="card-block">
                                    <small><?php echo $m->timestamp ?></small>
                                    <span><h5 class="card-title"><?php echo $m->sender()->name ?></h5></span>
                                    <p class="card-text"><?php echo $m->message ?></p>

                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="card">
                            <div class="card-block">
                                <span><h5 class="card-title">No messages</h5></span>
                            </div>
                        </div>
                        <?php
                    } ?>

                </div>
                <form method="post" name="group_chat">
                    <div align="center">
                        <textarea class="form-control" rows="4" name="group_chat"></textarea>
                        <p></p>
                        <button class="btn btn-accept" type="submit">Send</button>

                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <h3>List of members</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($members as $m) { ?>
                    <tr>
                        <td><?php if ($m->user == $circle->ownerById($circle_id)) {
                                echo $user->getNameById($m->user) . " (Admin)";
                            } else {
                                echo $user->getNameById($m->user);
                            } ?></td>
                        <td><a href="viewProfile.php?user=<?php echo $m->user ?>"
                               class="btn btn-sm btn-primary">Profile</a></td>
                        <td>
                            <?php if ($isViewingOwn) { ?>
                                <form class="form-inline" method="post" action="#" name="delete_admin">
                                    <input type="hidden" name="delete_admin" value="<?php echo $m->user ?>">
                                    <button class="btn btn-sm btn-primary" type="submit">Remove User</button>
                                </form>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>


            <h3>Add friends to the group</h3>
            <form action="#" method="post" name='add'>
                <div class="form-group">
                    <?php if ($session->hasErrors() && $session->getError("notFriends")) {
                        $error_msg = $session->getError("notFriends");
                        ?>
                        <span class="badge badge-danger"><?php echo $error_msg ?></span>
                    <?php } ?>
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
                <p><h4><a href=listCircle.php>Back to my Circles</h4>
            </form>
        </div>


    </div>
</div>

</body>
</html>
