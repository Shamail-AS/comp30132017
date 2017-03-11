<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 27/02/2017
 * Time: 19:39
 */

require_once('../Models/User.php');
require_once('../Models/Image.php');

require_once('../Core/SessionManager.php');
require_once('../Core/Validator.php');
require_once('../Core/FileManager.php');

use Database\Core\FileManager;
use Database\Models\Image;
use Database\Models\User;
use Http\Forms\Validator;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();

$user = new User($session->user->getAllData());
if(isset($_POST) && !empty($_POST)){

    $validator = new Validator();

    if(isset($_FILES)){
        $errors = $validator->validateImage($_FILES);
        if(count($errors) > 0 ){
            foreach ( $errors as $e=>$message){
                $session->addError($e,$message);
            }
        }
        if($session->hasErrors()){
            $session->redirect('editProfile');
        }
        $fileManager = FileManager::getInstance();
        $content = $fileManager->prepare("profile_pic");

        $splitted = explode('.',$_FILES["profile_pic"]["name"]);
        $ext = end($splitted);

        $image = new Image();
        $image->name = $_POST['title'];
        $image->description = $_POST['desc'] ?? '';
        $image->album_id = intval($user->proifilePicAlbum()->id);
        $image->album_id = 182;

        $result = $fileManager->upload($image->name.'.'.$ext,$content);
        if($result){
            $image->URL = $result;
            $image->save();
            $user->pic = $image->id;
            $session->user = $user;
        }
        else{
            $session->addError('file','Failed to upload');
        }
        $session->redirect('editProfile');

    }
    else{
        $errors = $validator->validateUserProfileData($_POST);
        if(count($errors) > 0 ){
            foreach ( $errors as $e=>$message){
                $session->addError($e,$message);
            }
        }
        if($session->hasErrors()){
            $session->redirect('editProfile');
        }
        else{

            $user->name = $_POST['name'];
            $user->email = $_POST['email'];
            $user->sex = $_POST['sex'];
            $user->interested_in = $_POST['interest'];
            $user->birthplace = $_POST['birthplace'];
            $user->work = $_POST['work'];
            $user->school = $_POST['school'];
            $user->dob = $_POST['dob'];
            $user->university = $_POST['university'];
            $user->save();
            $session->user = $user;
            $session->redirect('viewProfile');
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
    <link href="../Resources/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <h1>Edit Profile</h1>
    <hr>


    <?php if($user->profilePic()->URL == null){ ?>
        <div class="alert alert-warning">No profile picture yet</div>
    <?php } else{ ?>
        <img height="320px"
             src="<?php echo $user->profilePic()->URL ?>">
    <?php } ?>
    <form class="form form-inline" action="editProfile.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <input class="form-control-file" type="file" name="profile_pic" id="fileToUpload">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="name" placeholder="Name" name="title" required="">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </form>
    <br>
    <hr>
    <form action="editProfile.php" method="post">
        <div class="form-group">
            <div>
                <label>Name</label>
                <?php if ($session->hasErrors() && $session->getError("name")) {
                    $error_msg = $session->getError("name");                     ?>
                    <span class="badge badge-danger"><?php echo $error_msg ?></span>
                <?php } ?>
                <input type="text" class="form-control" id="name" value="<?php echo $user->name ?>"
                       placeholder="Name" name="name" required="">
            </div>
        </div>
        <div class="form-group">
            <div>
                <label>Email</label>
                <?php if ($session->hasErrors() && $session->getError("email")) {
                    $error_msg = $session->getError("email");                    ?>
                    <span class="badge badge-danger"><?php echo $error_msg ?></span>
                <?php } ?>
                <input type="text" class="form-control" id="email" value="<?php echo $user->email ?>"
                       placeholder="Email" name="email" required="">
            </div>
        </div>
        <div class="form-group">
            <label>Gender</label>
            <?php if ($session->hasErrors() && $session->getError("sex")) {
                $error_msg = $session->getError("sex");                 ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <select id="selSex" class="form-control" name="sex" title="Select">

                <option <?php echo ($user->sex == 'M') ? 'selected' : '' ?> value = "M">Male</option>
                <option <?php echo ($user->sex == 'F') ? 'selected' : '' ?> value = "F">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label>Interested In</label>
            <?php if ($session->hasErrors() && $session->getError("interested_in")) {
                $error_msg = $session->getError("interested_in");                 ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <select id="selSex" class="form-control" name="interest" title="Select">

                <option <?php echo ($user->interested_in == 'M') ? 'selected' : '' ?> value = "M">Male</option>
                <option <?php echo ($user->interested_in == 'F') ? 'selected' : '' ?> value = "F">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label>Birthplace</label>
            <?php if ($session->hasErrors() && $session->getError("birthplace")) {
                $error_msg = $session->getError("birthplace");                 ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <div>
                <input type="text" class="form-control" id="birthplace" value="<?php echo $user->birthplace ?>"
                       placeholder="Birthplace" name="birthplace" required="">
            </div>
        </div>
        <div class="form-group">
            <label>Workplace</label>
            <?php if ($session->hasErrors() && $session->getError("workplace")) {
                $error_msg = $session->getError("workplace");                 ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <div>
                <input type="text" class="form-control" id="work" value="<?php echo $user->work ?>"
                       placeholder="Work" name="work" required="">
            </div>
        </div>
        <div class="form-group">
            <label>School</label>
            <?php if ($session->hasErrors() && $session->getError("school")) {
                $error_msg = $session->getError("school");                 ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <div>
                <input type="text" class="form-control" id="school" value="<?php echo $user->school ?>"
                       placeholder="School" name="school" required="">
            </div>
        </div>
        <div class="form-group">
            <label>Date Of Birth</label>
            <?php if ($session->hasErrors() && $session->getError("dob")) {
                $error_msg = $session->getError("dob");                 ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <div>
                <input type="date" class="datepicker form-control" id="dob" value="<?php echo $user->dob ?>"
                       placeholder="Date Of Birth YYYY-mm-DD" name="dob" required="">
            </div>
        </div>
        <div class="form-group">
            <label>University</label>
            <?php if ($session->hasErrors() && $session->getError("university")) {
                $error_msg = $session->getError("university");                ?>
                <span class="badge badge-danger"><?php echo $error_msg ?></span>
            <?php } ?>
            <div>
                <input type="text" class="form-control" id="university" value="<?php echo $user->university ?>"
                       placeholder="University" name="university" required="">
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="viewProfile.php" type="button" class="btn btn-default" >Cancel</a>
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
<script src="../Resources/datepicker/js/bootstrap-datepicker.min.js"></script>

<script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
</script>
</body>
</html>


