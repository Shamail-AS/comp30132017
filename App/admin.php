<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 03/03/2017
 * Time: 07:12
 */
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');

use Database\Models\User;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;
if ($user->usertype != "ADMIN") {
    $session->redirect(home);
}
$user = new User();
$allUser = $user->all();


function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
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

    <title>Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include('common/nav.php') ?>
<div class="container">
<table class="table table-striped">
    <h1>Admin Interface</h1>
    <thead>
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Name</th>
        <th>User Type</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($allUser as $u) { ?>
        <tr>
            <td><?php echo $u->username ?></td>
            <td><?php echo $u->email ?></td>
            <td><?php echo $u->name ?></td>
            <td><?php echo $u->usertype ?></td>
            <td><a href="editUser.php?id=<?php echo $u->id ?>"
                   class="btn btn-sm btn-primary">Edit</a></td>
        </tr>
    <?php } ?>

    </tbody>
</table>
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
