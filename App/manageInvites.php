<?php

require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
require_once('../Models/Invite.php');

use Database\Models\Invite;
use Database\Models\User;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();

$user = $session->user;
$user = new User($user->getAllData());
$invites = $user->invites();

if (isset($_GET) && !empty($_GET)) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    $inv = new Invite();
    $invite = $inv->find($id);
    $invite->act($action);
    $session->flash("The invite was $action" . 'ed');
    $invites = $user->invites();
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

    <title>Manage invitations</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <h1>Manage Invites</h1>
    <?php if ($session->hasMessage()) { ?>
        <div class="alert alert-success"><?php echo $session->readMessage() ?></div>
    <?php } ?>
    <?php if (count($invites) == 0) { ?>
        <div class="alert alert-info">No invites pending</div>
    <?php } else {
        ?>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>Message</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($invites['all'] as $invite) { ?>

                <tr>
                    <td><?php echo $invite->isSentBy($user) ? 'Sent' : 'Received' ?></td>
                    <td><?php echo $invite->sender()->name ?></td>
                    <td><?php echo $invite->receiver()->name ?></td>
                    <td><?php echo $invite->message ?></td>
                    <td><?php echo $invite->status ?></td>
                    <td>
                        <?php if ($invite->status == 'pending') { ?>

                            <?php if (!$invite->isSentBy($user)) { ?>
                                <form class="form-inline" method="get" action="manageInvites.php">
                                    <input type="hidden" name="id" value="<?php echo $invite->id ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <button class="btn btn-sm btn-success" type="submit">Accept</button>
                                </form>
                                <form class="form-inline" method="get" action="manageInvites.php">
                                    <input type="hidden" name="id" value="<?php echo $invite->id ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button class="btn btn-sm btn-danger" type="submit">Reject</button>
                                </form>
                            <?php } else { ?>
                                <form class="form-inline" method="get" action="manageInvites.php">
                                    <input type="hidden" name="id" value="<?php echo $invite->id ?>">
                                    <input type="hidden" name="action" value="cancel">
                                    <button class="btn btn-sm btn-default" type="submit">Withdraw</button>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

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


