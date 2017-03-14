<?php
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
require_once('../Models/Album.php');
require_once('../Models/Circles_Member.php');

use Database\Models\Image;
use Database\Models\User;
use Database\Models\Album;
use Http\Session\SessionManager;
use Database\Models\Circles_Member;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;

if (!isset($_GET['user'])) {
    // Fallback behaviour goes here
    $session->redirect('home');
}

$user_id = $_GET['user'];
$album = new Album();
$albums = $album->getByUser($user_id);
$displayGroup = 0;
if ($_GET['user'] == $user->id) {
    $displayGroup = 1;
}
//pr($albums);
function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 20/02/2017
 * Time: 04:56
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <style>
        .photo-box {
            width: 30%;
            list-style-type: none;
            display: block;
            float: left;
            margin: 10px 10px 10px 10px;
        }
        img {
            width: 100%;
            height: auto;
        }
    </style>


    <title>View Album</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div style="display:inline-block;margin-left: 15%;margin-right: 15%; width: 70%" class="container;text-align: center">
    <div class="starter-template" style="text-align: center">
        <?php
        $url = "viewAlbum.php?id=" . $user_id;
        $u = new User();
        $name = $u->getNameById($user_id);
        echo "<h1>All Album By " . $name . "</h1>";

        if ($user_id == $user->id) {
            echo '<a href="createAlbum.php"
           class="btn btn-md btn-primary">Create Album</a><br>';
        };
        ?>
    </div>
    <p>
        <?php
        $thumbnail = "http://www.graphicsfuel.com/wp-content/uploads/2012/03/folder-icon-512x512.png";
        if (!empty($albums)) {
            foreach ($albums as $a) {
                echo "                <li class=\"photo-box\">
                    <div class=\"image-wrap\">
                        <img src=" . $thumbnail . ">
                    </div>
                    <div class=\"description\" style='text-align: center'>
                        <h5><a href=\"viewAlbum.php?id=" . $a->id . "\">" . $a->name . "</a></h5>
                    </div>
                </li>";
            }
        }
        else {
            echo "No Album";
        }
        ?>
    </p>
</div>
<div style="display:inline-block;margin-left: 15%;margin-right: 15%; width: 70%;text-align: center" class="container">
    <div >
        <?php
        if ($displayGroup)
            echo "<h1>All album shared by group</h1>";
        ?>
    </div>
    <p>
        <?php
        if ($displayGroup) {
            $sharedAlbum = new Album();
            $circles = new Circles_Member();
            $mycircles = $circles->getByUser($user->id);
            if (count($mycircles) > 0) {
                foreach ($mycircles as $c) {
                    //if ($c->user != $user_id) {
                    $sharedAlbums = $sharedAlbum->getAlbumByCircleID($c->circle);
                    foreach ($sharedAlbums as $s) {
                        if ($s->user_id != $user_id) {
                            $thumbnail = "http://www.graphicsfuel.com/wp-content/uploads/2012/03/folder-icon-512x512.png";
                            echo "                <li class=\"photo-box\">
                    <div class=\"image-wrap\">
                        <img src=" . $thumbnail . ">
                    </div>
                    <div class=\"description\" style='text-align: center'>
                        <h5><a href=\"viewAlbum.php?id=" . $s->id . "\">" . $s->name . "</a></h5>
                    </div>
                    </li>";
                        }
                    }
                }
            }
            else {
                echo "You are not in any circle";
            }
        }
        ?>
    </p>
</div>


</body>
</html>
