<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 03/03/2017
 * Time: 13:03
 */
require_once('../Models/Album.php');
require_once('../Models/Privacy.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
require_once('../Models/Circle.php');
require_once('../Models/Circles_Member.php');

use Database\Models\Album;
use Database\Models\Privacy;
use Database\Models\Circle;
use Http\Session\SessionManager;
use Database\Models\Circles_Member;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;

$circle = new Circle();
$circles = new Circles_Member();
$mycircles = $circles->getByUser($user->id);

$privacy = new Privacy();
$optList = $privacy->listAll();
if (!isset($_GET['id'])) {
    // Fallback behaviour goes here
    $session->redirect('home');
}
$album_id = $_GET['id'];

$album = new Album();

$a = $album->getByID($album_id);

if ($a->user_id != $user->id) {
    $session->redirect('home');
}
$assigned = $album->getAssignedCircle($album_id);
$foo = array();
$i = 0;
foreach ($assigned as $as) {
    $foo[$i] = $as->circle;
    $i = $i + 1;
}
//pr($foo);
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit an album</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>

    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">

    <div class="starter-template">
        <h1>Edit Album</h1>
        <?php if ($session->hasErrors() && $session->getError("albumCreated")) {
            $error_msg = $session->getError("albumCreated");
            ?>
            <span class="badge badge-danger"><?php echo $error_msg ?></span>
        <?php } ?>
    </div>
    <div>
        <form>
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
                    <option>Privacy Level</option>
                    <?php
                    foreach ($optList as $opt)
                    {
                        echo '<option value="' .$opt->id. '">' . $opt->description . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Grant Access To Circle</label>
                <select id="selCircle" multiple="multiple" style="width:100%">
                    <?php
                    if (count($mycircles) > 0) {
                        foreach ($mycircles as $c) {
                            echo '<option value="' . $c->circle . '">' . $circle->idToName($c->circle) . "</option>";
                            //echo '<option val = "' . $c->circle . '" >' . $c->circle . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button id="submitBtn" class="btn btn-default">Submit</button>
            </div>
        </form>
    </div>

</div>

<script>
    $(document).ready(function() {
        var selVal = [];
        var delVal = [];
        $('#name').val(<?php echo json_encode($a->name) ?>);
        plevel = <?php echo json_encode($a->privacy_level) ?>;
        album_id = <?php echo json_encode($album_id) ?>;
        $('#plevel').val(plevel).change();

        $("#selCircle").select2();

        selVal =  <?php echo json_encode($foo) ?>;
        $("#selCircle").val(selVal).trigger("change");

        $("#selCircle").on("select2:select", function(e) {
            selVal.push(e.params.data.id);
        });

        $("#selCircle").on("select2:unselect", function(e) {
            delVal.push(e.params.data.id);
        });

        $('#submitBtn').click(function(e) {
            e.preventDefault();
            album_name = $('#name').val();
            plevel = $('#plevel').val();

            $.post( "albumController.php", { id: album_id, name: album_name, plevel: plevel, selGroup: selVal, delGroup: delVal } ,function(response,status){
                alert("Success");
            });
            location.reload();
        });

    });
</script>
</body>
</html>
