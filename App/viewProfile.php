<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 27/02/2017
 * Time: 19:38
 */
include_once('../Core/SessionManager.php');
include_once('../Core/PrivacyManager.php');
include_once('../Models/User.php');
include_once('../Models/Friendship.php');

use Database\Core\PrivacyManager;
use Database\Models\Friendship;
use Database\Models\User;
use Http\Session\SessionManager;


$session = new SessionManager();


$session->start();
$session->blockGuest();
$u = new User();

$logged_user = new User($session->user->getAllData());
$view_user = $logged_user;
$isViewingOwn = true;
$canView = true;

if (isset($_GET['user']) && !empty($_GET)) {
    $view_user = $u->find($_GET['user']);
    $canView = PrivacyManager::canViewProfile($view_user, $logged_user);
    $isViewingOwn = false;
    $friendship = new Friendship();
    $view_user->similarity = $friendship->getSimilarity($logged_user, $view_user);
}

$data = $view_user->getAllData();
$updatedUser = $u->find($view_user->id);
$userData = $updatedUser->getAllData();

if (isset($_GET['export'])) {
    @date_default_timezone_set("GMT");
    $writer = new XMLWriter();

    $writer->openURI('data.xml');
    $writer->startDocument('1.0');
    $writer->setIndent(4);
    $writer ->startElement("User_Profile");
    foreach ($data as $key=> $value) {
        $writer->writeElement($key, $value);
    }
    $writer->endElement();
    $writer->endDocument();
}

if (isset($_GET['import'])){
    $reader = new XMLReader();
    if (!$reader->open('xml_file/data.xml')) {
        die("Failed to open data.xml");
    }

    while($reader->read()) {
        $node = $reader->expand();
        if(strpos($node->nodeName,'#text') > -1) continue;
        if(strlen($node->nodeValue) < 1) continue;
        if(!array_key_exists( $node->nodeName,$userData )) continue;
        #echo($node->nodeName."<br>");
        $view_user->set($node->nodeName, $node->nodeValue);
    }
    #var_dump($updatedUser->getAllData());
    $view_user->save();
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

    <title>Profile</title>
    <script language="JavaScript" type="text/javascript">
        function exporting() {
            window.location.href = 'viewProfile.php?export';
            alert("Exported to xml_file folder");

        }
        function importing() {
            window.location.href = 'viewProfile.php?import';
            alert("Import from xml_file folder");

        }
    </script>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">
    <?php if (!$canView) { ?>
        <div class="alert alert-danger">You can't view this profile due to privacy settings of the owner</div>
        <?php exit();
    } ?>
    <h1>Profile Details <?php if ($isViewingOwn) { ?> <a class="badge badge-info"
                                                         href="editProfile.php">Edit</a><?php } ?></h1>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <?php if (!$isViewingOwn) { ?>
                <h3>Similarity score</h3>
                <p><?php echo $view_user->similarity ?></p>
            <?php } ?>
            <h3>Name</h3>
            <p><?php echo $view_user->name ?></p>
            <h3>Birthday</h3>
            <p><?php echo $view_user->dob ?></p>
            <h3>Birthplace</h3>
            <p><?php echo $view_user->birthplace ?></p>
            <h3>Work</h3>
            <p><?php echo $view_user->work ?></p>
            <h3>School</h3>
            <p><?php echo $view_user->school ?></p>
            <h3>University</h3>
            <p><?php echo $view_user->university ?></p>
            <h3>Sex</h3>
            <p><?php echo $view_user->sex() ?></p>
            <h3>Interested in</h3>
            <p><?php echo $view_user->interested_in() ?></p>
            <?php
            if (isset($_GET['user']) && !empty($_GET)) {
                $uid = $_GET['user'];
            } else {
                $uid = $user->id;
            }
                $url = "listAlbum.php?user=" . $uid;
                echo "<h3><a href= ". $url . ">Show All Album By " . $view_user->name . "</a></h3>";
            ?>
        </div>
        <div class="col-md-6">
            <img height="320px"
                 src="<?php echo $view_user->profilePic()->URL ?>">
            <?php if($view_user->profilePic()->URL == null){ ?>
            <div class="alert alert-warning">No profile picture yet</div>
            <?php } ?>
        </div>
    </div>
    <hr>
    <h1>Friends</h1>
    <hr>

    <?php foreach ($view_user->getFriends() as $friend) {
        if ($friend->id == $session->user->id) continue; ?>
        <li class="list-group-item">
            <div class="search-result">
                <a href="viewProfile.php?user=<?php echo $friend->id ?>"><p><?php echo $friend->name ?></p></a>
                <p><?php echo $friend->email ?></p>
                <?php if (PrivacyManager::canSendConnectionRequests($friend, $session->user)) { ?>
                    <?php if (!$user->hasContacted($friend)) { ?>
                        <a href="sendInvite.php?user=<?php echo $friend->id ?>" class="btn btn-primary"
                           type="button">Send
                            request</a>
                    <?php } else {
                        echo "A connection request already exists";
                        ?>
                        <a href="manageInvites.php" class="btn btn-default" type="button">Requests</a>
                    <?php }
                } else {
                    echo "Can't send connection request due to privacy settings";
                } ?>
            </div>
        </li>
        <br>

    <?php } ?>

    <?php if ($isViewingOwn) { ?>
    <h1>Data</h1>
    <hr>

    <input class="btn btn-primary" type='button' value='Export' onclick = 'exporting()'><?php } ?>
    <?php if ($isViewingOwn) { ?><input class="btn btn-primary" type='button' value='Import' onclick = 'importing()'><?php } ?>
    <br>
    <br>
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


