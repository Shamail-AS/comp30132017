<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 08/02/2017
 * Time: 17:35
 */
require_once('../Models/Album.php');
require_once('../Models/Privacy.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');

use Database\Models\Album;
use Database\Models\Privacy;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;

$privacy = new Privacy();
$optList = $privacy->listAll();
function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}

if (isset($_POST) && !empty($_POST)) {
    $album = new Album();
    $album->name = $_POST['name'];
    $album->user_id = $user->id; //maybe $SESSION->('userid');
    $plevel2 = $privacy->getIdByName(($_POST['plevel']));
    $album->privacy_level = $privacy->getIdByName(($_POST['plevel']));
    if ($album->isExisted()) {
        $session->addError('albumExisted', 'Please choose a different name');
        $session->redirect('createAlbum');
    } else {
        $session->addError('albumCreated', 'Successfully Created');
        $album->save();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Make a Post</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">

    <div class="starter-template">
        <h1>Create Album</h1>
        <?php if ($session->hasErrors() && $session->getError("albumCreated")) {
            $error_msg = $session->getError("albumCreated");
            ?>
            <span class="badge badge-danger"><?php echo $error_msg ?></span>
        <?php } ?>
    </div>
    <div>
        <form action="createAlbum.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <?php if ($session->hasErrors() && $session->getError("albumExisted")) {
                    $error_msg = $session->getError("albumExisted");
                    ?>
                    <span class="badge badge-danger"><?php echo $error_msg ?></span>
                <?php } ?>
                <div>
                    <input type="text" class="form-control" id="name"
                           placeholder="Name" name="name" required="">
                </div>
            </div>
            <div class="form-group">
                <select name="plevel" id="plevel" class="form-control" title="Select">
                    <?php
                    foreach ($optList as $opt)
                    {
                        echo "<option id = $opt->id>".$opt->description."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </form>
    </div>

</div>


</body>
</html>