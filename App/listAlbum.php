<?php
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
require_once('../Models/Album.php');

use Database\Models\Image;
use Database\Models\User;
use Database\Models\Album;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;
$user = new User($user->getAllData());

if (!isset($_GET['user'])) {
    // Fallback behaviour goes here
    $session->redirect('home');
}

$user_id = $_GET['user'];
$album = new Album();
$albums = $album->getByUser($user_id);
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
<div class="container">
    <div class="starter-template">
        <?php
        $url = "viewAlbum.php?id=" . $user_id;
        $u = new User();
        $name = $u->getNameById($user_id);
        echo "<h1>All Album By " . $name . "</h1>"
        ?>
        <h5><a href= "home.php">Back To Home</a></h5>
    </div>
    <div>
        <?php
        $thumbnail = "http://www.graphicsfuel.com/wp-content/uploads/2012/03/folder-icon-512x512.png";
        if (!empty($albums)) {
            foreach ($albums as $a) {
                echo "                <li class=\"photo-box\">
                    <div class=\"image-wrap\">
                        <img src=" . $thumbnail . ">
                    </div>
                    <div class=\"description\">
                        <h5><a href=\"viewAlbum.php?id=" . $a->id . "\">" . $a->name . "</a></h5>
                    </div>
                </li>";
            }
        }
        else {
            echo "No Album";
        }
        ?>

    </div>
</div>


</body>
</html>
