<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 07/02/2017
 * Time: 14:00
 */
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
require_once('../Models/Friendship.php');

use Database\Models\Friendship;
use Database\Models\User;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;
$user = new User($user->getAllData());



$friendships = $user->getFriends();

$suggestions = $user->getFriendsOfFriends();

$friendship = new Friendship();
$suggestions = $friendship->suggestionsFor($user);
//var_dump($suggestions_extra);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Fixed top navbar example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <h1>Your friends</h1>

    <?php if (count($friendships) == 0) { ?>
        <div class="alert alert-info">No friends</div>
    <?php } else {
        ?>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($friendships as $friend) { ?>
                <tr>
                    <td><?php echo $friend->name ?></td>
                    <td><?php echo $friend->email ?></td>
                    <td><a href="viewProfile.php?user=<?php echo $friend->id ?>"
                           class="btn btn-sm btn-primary">Profile</a></td>
                </tr>
            <?php } ?>

            </tbody>
        </table>

    <?php } ?>

    <h1>Suggested friends</h1>

    <?php if (count($suggestions) == 0) { ?>
        <div class="alert alert-info">No friends</div>
    <?php } else {
        ?>

        <ul class="list-group">
            <?php foreach ($suggestions as $s_user) { ?>
                <li class="list-group-item">
                    <div class="search-result">
                        <p><?php echo $s_user->name ?></p>
                        <p><?php echo $s_user->email ?></p>
                        <?php if (!$user->hasContacted($s_user)) { ?>
                            <a href="sendInvite.php?user=<?php echo $s_user->id ?>" class="btn btn-primary"
                               type="button">Send
                                request</a>
                        <?php } else {
                            echo "A connection request already exists";
                            ?>
                            <a href="manageInvites.php" class="btn btn-default" type="button">Requests</a>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
        </ul>

    <?php } ?>


</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
        integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="../Resources/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>




