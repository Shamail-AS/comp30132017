<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 27/02/2017
 * Time: 19:38
 */
include_once('../Core/SessionManager.php');
include_once('../Core/PrivacyManager.php');
include_once('../Models/User.php');
include_once('../Models/Friendship.php');

use Database\Core\PrivacyManager;
use Database\Models\Friendship;
use Database\Models\User;
use Http\Session\SessionManager;


$session = new SessionManager();


$session->start();
$session->blockGuest();
$u = new User();

$logged_user = new User($session->user->getAllData());
$view_user = $logged_user;
$isViewingOwn = true;
$canView = true;

if (isset($_GET) && !empty($_GET)) {
    $view_user = $u->find($_GET['user']);
    $canView = PrivacyManager::canViewProfile($view_user, $logged_user);
    $isViewingOwn = false;
    $friendship = new Friendship();
    $view_user->similarity = $friendship->getSimilarity($logged_user, $view_user);
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

    <title>Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <?php if (!$canView) { ?>
        <div class="alert alert-danger">You can't view this profile due to privacy settings of the owner</div>
        <?php exit();
    } ?>
    <h1>Profile Details <?php if ($isViewingOwn) { ?> <a class="badge badge-info"
                                                         href="editProfile.php">Edit</a><?php } ?></h1>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <?php if (!$isViewingOwn) { ?>
                <h3>Similarity score</h3>
                <p><?php echo $view_user->similarity ?></p>
            <?php } ?>
            <h3>Name</h3>
            <p><?php echo $view_user->name ?></p>
            <h3>Birthday</h3>
            <p><?php echo $view_user->dob ?></p>
            <h3>Birthplace</h3>
            <p><?php echo $view_user->birthplace ?></p>
            <h3>Work</h3>
            <p><?php echo $view_user->work ?></p>
            <h3>School</h3>
            <p><?php echo $view_user->school ?></p>
            <h3>University</h3>
            <p><?php echo $view_user->university ?></p>
            <h3>Sex</h3>
            <p><?php echo $view_user->sex() ?></p>
            <h3>Interested in</h3>
            <p><?php echo $view_user->interested_in() ?></p>
        </div>
        <div class="col-md-6">
            <img height="320px"
                 src="<?php echo $view_user->profilePic()->URL ?>">
            <?php if($view_user->profilePic()->URL == null){ ?>
            <div class="alert alert-warning">No profile picture yet</div>
            <?php } ?>
        </div>
    </div>
    <hr>
    <h1>Friends</h1>
    <hr>

    <?php foreach ($view_user->getFriends() as $friend) {
        if ($friend->id == $session->user->id) continue; ?>
        <li class="list-group-item">
            <div class="search-result">
                <a href="viewProfile.php?user=<?php echo $friend->id ?>"><p><?php echo $friend->name ?></p></a>
                <p><?php echo $friend->email ?></p>
                <?php if (PrivacyManager::canSendConnectionRequests($friend, $session->user)) { ?>
                    <?php if (!$user->hasContacted($friend)) { ?>
                        <a href="sendInvite.php?user=<?php echo $friend->id ?>" class="btn btn-primary"
                           type="button">Send
                            request</a>
                    <?php } else {
                        echo "A connection request already exists";
                        ?>
                        <a href="manageInvites.php" class="btn btn-default" type="button">Requests</a>
                    <?php }
                } else {
                    echo "Can't send connection request due to privacy settings";
                } ?>
            </div>
        </li>
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


