<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 07/02/2017
 * Time: 14:09
 */
require_once('../Models/User.php');
require_once('../Core/Validator.php');
require_once('../Core/SessionManager.php');

use Database\Models\User;
use Http\Forms\Validator;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->onlyGuest();
if (isset($_POST) && !empty($_POST)) {
    $validator = new Validator();
    $errors = $validator->validateUserLoginData($_POST);
    if (count($errors) > 0) {
        foreach ($errors as $key => $value) {
            $session->addError($key, $value);
        }
    }

    if ($session->hasErrors()) {
        $session->redirect('login');
    } else {
        $username = $_POST['username'];
        $pass = $_POST['password'];

        $u = new User();
        $user = $u->findByColumn('username', $username)[0];
        if ($user == null) {
            $session->addError('no_user', 'username not registered');
            $session->redirect('login');
        } else {
            $act_pass = $user->password;
            if (!password_verify($pass, $act_pass)) {
                $session->addError('no_pass', 'password mismatch');
                $session->redirect('login');
            } else {
                $session->user = $user;
                $session->redirect('home');
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

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <h1>Login to Soc.al</h1>
    <form action="login.php" method="post">
        <div class="form-group">
            <label>Username</label>
            <?php if ($session->hasErrors() && $session->getError("username")) {
                $error_msg = $session->getError("username");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>

            <?php if ($session->hasErrors() && $session->getError("no_user")) {
                $error_msg = $session->getError("no_user");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <input type="text" name="username" class="form-control" placeholder="Username">

        </div>
        <div class="form-group">
            <label>Password</label>
            <?php if ($session->hasErrors() && $session->getError("password")) {
                $error_msg = $session->getError("password");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <?php if ($session->hasErrors() && $session->getError("no_pass")) {
                $error_msg = $session->getError("no_pass");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <input type="password" name="password" class="form-control" placeholder="Password">

        </div>

        <button type="submit" class="btn btn-primary">Login</button>
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


