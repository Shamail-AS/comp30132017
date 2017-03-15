<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 03/03/2017
 * Time: 07:54
 */
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
require_once('../Core/Validator.php');

use Database\Models\User;
use Http\Forms\Validator;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();


$user_id = $_GET['id'];
$user = new User();
$u = $user->getUserById($user_id);
$user = $session->user;
if ($user->usertype != "ADMIN") {
    $session->redirect('home');
}
if (isset($_POST) && !empty($_POST)) {
    $validator = new Validator();
    $errors = $validator->validateUserAdminData($_POST);
    if (count($errors) > 0) {
        foreach ($errors as $key => $value) {
            $session->addError($key, $value);
        }
    }
}
    //var_dump($session->errors());
    if ($session->hasErrors()) {
    //add redirection back to form
    $session->redirect('editUser?id='.$user_id);

    } else {
    if (array_key_exists('selSex', $_POST)) {
        $u->name = $_POST['name'];
        $u->email = $_POST['email'];
        if ($_POST['selSex'] == "Female") {
            $u->sex = "F";
        } elseif ($_POST['selSex'] == "Male") {
            $u->sex = "M";

            if ($_POST['selUserType'] == "ADMIN") {
                $u->usertype = "ADMIN";
            } elseif ($_POST['selUserType'] == "USER") {
                $u->usertype = "USER";
            }

            $u->birthplace = $_POST['birthplace'];
            $u->work = $_POST['work'];
            $u->school = $_POST['school'];
            $u->dob = $_POST['dob'];
            $u->university = $_POST['university'];
            $u->save();
        }
    }
}

function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}

//pr($u);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit User Detail</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>

</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">

    <div class="starter-template">
        <h1>Edit User Detail</h1>
    </div>
    <div>
        <?php
        //$action = "editUser.php?id="  .$user_id;
        //echo "<form action=". $action." method=\"post\" enctype=\"multipart/form-data\">";
        ?>
        <form action="<?php echo "editUser.php?id=$user_id" ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div>
                    <label>Name</label>
                    <?php if ($session->hasErrors() && $session->getError("name")) {
                    $error_msg = $session->getError("name");                     ?>
                        <span class="badge badge-danger"><?php echo $error_msg ?></span>
                    <?php } ?>
                    <input type="text" class="form-control" id="name"
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
                    <input type="text" class="form-control" id="email"
                           placeholder="Email" name="email" required="">
                </div>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <?php if ($session->hasErrors() && $session->getError("sex")) {
                    $error_msg = $session->getError("sex");                 ?>
                    <span class="badge badge-danger"><?php echo $error_msg ?></span>
                <?php } ?>
                <select id="selSex" class="form-control" name="selSex" title="Select">
                    <option>Select Gender</option>
                    <option value = "Male">Male</option>
                    <option value = "Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>User Type</label>
                <?php if ($session->hasErrors() && $session->getError("usertype")) {
                $error_msg = $session->getError("usertype"); ?>
                    <span class="badge badge-danger"><?php echo $error_msg ?></span>
                <?php } ?>
                <select id="selUserType" class="form-control" name="selUserType" title="Select">
                    <option>Select User Type</option>
                    <option value = "USER">USER</option>
                    <option value = "ADMIN">ADMIN</option>
                </select>
            </div>
            <div class="form-group">
                <label>Birthplace</label>
                <?php if ($session->hasErrors() && $session->getError("birthplace")) {
                $error_msg = $session->getError("birthplace");                 ?>
                    <span class="badge badge-danger"><?php echo $error_msg ?></span>
                <?php } ?>
                <div>
                    <input type="text" class="form-control" id="birthplace"
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
                    <input type="text" class="form-control" id="work"
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
                    <input type="text" class="form-control" id="school"
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
                    <input type="text" class="form-control" id="dob"
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
                    <input type="text" class="form-control" id="university"
                           placeholder="University" name="university" required="">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </form>
    </div>

</div>
<script>
    $(document).ready(function() {
        console.log(<?php echo json_encode($u); ?>);
        $('#name').val(<?php echo json_encode($u->name); ?>);
        $('#email').val(<?php echo json_encode($u->email); ?>);

        if (<?php echo json_encode($u->sex); ?> == "M")
        {
            $('#selSex').val("Male").change();
        }
        else {
            $('#selSex').val("Female").change();
        }

        if (<?php echo json_encode($u->usertype); ?> == "ADMIN") {
            $('#selUserType').val("ADMIN").change();
        } else {
            $('#selUserType').val("USER").change();
        }

        $('#birthplace').val(<?php echo json_encode($u->birthplace); ?>);
        $('#work').val(<?php echo json_encode($u->work); ?>);
        $('#school').val(<?php echo json_encode($u->school); ?>);
        $('#dob').val(<?php echo json_encode($u->dob); ?>);
        $('#university').val(<?php echo json_encode($u->university); ?>);
    })
</script>
</body>
</html>