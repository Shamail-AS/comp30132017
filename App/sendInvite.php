<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 11/02/2017
 * Time: 11:30
 */
require_once('../Models/Invite.php');
require_once('../Models/User.php');
require_once('../Core/SessionManager.php');

use Database\Models\Invite;
use Database\Models\User;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();

$logged_in_user = $session->user;
$to_user_id = null;

if (isset($_GET) && !empty($_GET)) {
    $user = new User();
    $to_user_id = $_GET['user'];
    $to_user = $user->find($to_user_id);

}
if (isset($_POST) && !empty($_POST)) {
    $invite = new Invite();
    $invite->user1 = $logged_in_user->id;
    $invite->user2 = $_POST['receiver'];
    $invite->message = $_POST['message'];
    $invite->status = 'pending';
    $invite->send();
    //var_dump($_POST);
    //var_dump($invite);
    $session->redirect('manageInvites');
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
    <h1>Send connection request</h1>
    <h3>to <?php echo $to_user->name ?></h3>
    <form class="form" method="post" action="sendInvite.php">
        <div class="form-group">
            <input type="hidden" value="<?php echo $to_user_id ?>" name="receiver">
            <input class="form-control" type="text" name="message" placeholder="enter message">
            <button class="btn btn-success" type="submit">Send Invite</button>
            <button onclick="window.history.back()" class="btn btn-warning" type="button">Cancel</button>
        </div>
    </form>
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



