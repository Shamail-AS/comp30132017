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

if (!isset($_GET['id'])) {
    // Fallback behaviour goes here
    //$session->redirect('listCircle');
}

$circle_id = $_GET['id'];

if (isset($_POST) && !empty($_POST)) {
    $album = new Album();
    $album->name = $_POST['name'];
    $album->user_id = $user->id; //maybe $SESSION->('userid');
    $plevel2 = $privacy->getIdByName(($_POST['plevel']));
    $album->privacy_level = $privacy->getIdByName(($_POST['plevel']));
    if ($album->isExisted()) {
        $session->addError('albumExisted', 'Please choose a different name');
        $session->redirect('createAlbumCircle');
    } else {
        $session->addError('albumCreated', 'Successfully Created');
        $album->createAlbumForCircle($circle_id);
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

    <title>Make a circle album</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>
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
        <?php
            $formurl = "createAlbumCircle.php?id=" . $circle_id;
            echo '<form action="' . $formurl . '" method="post" enctype="multipart/form-data">';
        ?>
            <div class="form-group">
                <label>Name</label>
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
                <label>Privacy Level</label>
                <select name="plevel" id="plevel" class="form-control" title="Select">
                    <?php
                    foreach ($optList as $opt)
                    {
                        if ($opt->id == 1 || $opt->id == 4) {
                            echo "<option id = $opt->id>" . $opt->description . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Circle ID</label>
                <div>
                    <input type="text" class="form-control" id="circleid"
                           placeholder="Name" name="circleid" disabled>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </form>
    </div>

</div>

<script>
    $('#circleid').val(<?php echo json_encode($circle_id); ?>);
</script>
</body>
</html>