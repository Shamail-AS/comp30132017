<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 10/02/2017
 * Time: 13:16
 */
require_once('../Core/SessionManager.php');
require_once('../Core/PrivacyManager.php');
require_once('../Models/User.php');
require_once('../Models/Friendship.php');
use Database\Core\PrivacyManager;
use Database\Models\Friendship;
use Database\Models\User;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();


$users = [];
$searcher = "";
$searching = false;
if (isset($_GET) && !empty($_GET)) {
    $searcher = $_GET['searcher'];
    if (strlen($searcher) > 0) {
        $searching = true;
        $u = new User();
        $users = $u->where(null, "users.name LIKE '%$searcher%'");
        $f = new Friendship();
        for ($i = 0; $i < count($users); $i++) {
            $user = $users[$i];

            if (!PrivacyManager::canSearchProfile($user, $session->user)) {
                $user->hidden = true;
            }
            if ($f->areFriends($user, $session->user)) {
                $user->isFriend = true;
            }
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
    <link rel="icon" href="../../favicon.ico">

    <title>Fixed top navbar example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <h1>Search Soci.al Directory to friend users</h1>
    <form class="form-inline" method="get" action="searchUsers.php">
        <div class="form-group">
            <input class="form-control" type="text" name="searcher" placeholder="enter user name">
            <button class="btn btn-default" type="submit">Search</button>
        </div>
    </form>

    <?php if ($searching && count($users) == 0) { ?>
        <div class="alert alert-info">No results found for <?php echo $searcher ?></div>
    <?php } else {
        ?>
        <ul class="list-group">
            <?php foreach ($users as $user) { ?>
                <li class="list-group-item">
                    <div class="search-result">
                        <?php if ($user->hidden) {
                            echo "result hidden due to privacy settings";
                            continue;
                        }
                        ?>
                        <a href="viewProfile.php?user=<?php echo $user->id ?>"><p><?php echo $user->name;
                                if ($user->isFriend) echo " (Friend)"; ?></p></a>
                        <p><?php echo $user->email ?></p>
                        <?php if (!$user->isFriend && PrivacyManager::canSendConnectionRequests($user, $session->user)) { ?>
                            <a href="sendInvite.php?user=<?php echo $user->id ?>" class="btn btn-primary" type="button">Send
                                request</a>
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




