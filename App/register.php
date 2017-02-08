<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 06/02/2017
 * Time: 15:24
 */
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
require_once('../Core/Validator.php');

use Database\Models\User;
use Http\Forms\Validator;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
if (isset($_POST) && !empty($_POST)) {

    $validator = new Validator();
    $errors = $validator->validateUserRegistrationData($_POST);
    if (count($errors) > 0) {
        foreach ($errors as $key => $value) {
            $session->addError($key, $value);
        }
    } else {
        //$session->clean();
    }
    //var_dump($session->errors());
    if ($session->hasErrors()) {
        //add redirection back to form
        $session->redirect('register');
        //var_dump($session->errors());
    } else {
        $user = new User();
        $user->username = $_POST['username'];
        $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user->email = $_POST['email'];
        $user->name = $_POST['name'];
        if ($user->isRegistered()) {
            $session->addError('registration', 'You are already registered');
            $session->redirect('register');
        } else {
            $user->save();
            var_dump($user);
            if (!$user->exists()) {
                $session->addError('save', 'Could not save user');
                $session->redirect('register');
            } else {
                //redirect to home page because user has logged in
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

    <title>Fixed top navbar example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <h1>Sign up with Soc.al</h1>
    <?php if ($session->hasErrors() && $session->getError('registration')) { ?>
        <div class="alert alert-warning">
            You are already registered. <a href="login.php">Login here</a>
        </div>
    <?php } ?>

    <form action="register.php" method="post">
        <div class="form-group">
            <label>Name</label>
            <?php if ($session->hasErrors() && $session->getError("name")) {
                $error_msg = $session->getError("name");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <input type="name" name="name" class="form-control" placeholder="Enter your name">

        </div>
        <div class="form-group">
            <label>Email address</label>
            <?php if ($session->hasErrors() && $session->getError("email")) {
                $error_msg = $session->getError("email");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <input type="email" name="email" class="form-control" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label>Username</label>
            <?php if ($session->hasErrors() && $session->getError("username")) {
                $error_msg = $session->getError("username");
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
            <input type="password" name="password" class="form-control" placeholder="Password">

        </div>
        <div class="form-group">
            <label>Confirm password</label>
            <?php if ($session->hasErrors() && $session->getError("conf_password")) {
                $error_msg = $session->getError("conf_password");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>

            <?php if ($session->hasErrors() && $session->getError("match")) {
                $error_msg = $session->getError("match");
                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>

            <input type="password" name="conf_password" class="form-control" placeholder="Retype password">
        </div>
        <!--        <div class="form-group">-->
        <!--            <label for="exampleSelect1">Example select</label>-->
        <!--            <select class="form-control" id="exampleSelect1">-->
        <!--                <option>1</option>-->
        <!--                <option>2</option>-->
        <!--                <option>3</option>-->
        <!--                <option>4</option>-->
        <!--                <option>5</option>-->
        <!--            </select>-->
        <!--        </div>-->
        <!--        <div class="form-group">-->
        <!--            <label for="exampleSelect2">Example multiple select</label>-->
        <!--            <select multiple class="form-control" id="exampleSelect2">-->
        <!--                <option>1</option>-->
        <!--                <option>2</option>-->
        <!--                <option>3</option>-->
        <!--                <option>4</option>-->
        <!--                <option>5</option>-->
        <!--            </select>-->
        <!--        </div>-->
        <!--        <div class="form-group">-->
        <!--            <label for="exampleTextarea">Example textarea</label>-->
        <!--            <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>-->
        <!--        </div>-->
        <!--        <div class="form-group">-->
        <!--            <label for="exampleInputFile">File input</label>-->
        <!--            <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">-->
        <!--            <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>-->
        <!--        </div>-->
        <!--        <fieldset class="form-group">-->
        <!--            <legend>Radio buttons</legend>-->
        <!--            <div class="form-check">-->
        <!--                <label class="form-check-label">-->
        <!--                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked>-->
        <!--                    Option one is this and that&mdash;be sure to include why it's great-->
        <!--                </label>-->
        <!--            </div>-->
        <!--            <div class="form-check">-->
        <!--                <label class="form-check-label">-->
        <!--                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2">-->
        <!--                    Option two can be something else and selecting it will deselect option one-->
        <!--                </label>-->
        <!--            </div>-->
        <!--            <div class="form-check disabled">-->
        <!--                <label class="form-check-label">-->
        <!--                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="option3" disabled>-->
        <!--                    Option three is disabled-->
        <!--                </label>-->
        <!--            </div>-->
        <!--        </fieldset>-->
        <!--        <div class="form-check">-->
        <!--            <label class="form-check-label">-->
        <!--                <input type="checkbox" class="form-check-input">-->
        <!--                Check me out-->
        <!--            </label>-->
        <!--        </div>-->
        <button type="submit" class="btn btn-primary">Submit</button>
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


