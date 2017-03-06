<?php
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
require_once('../Models/Image.php');
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

if (!isset($_GET['id'])) {
    // Fallback behaviour goes here
    $session->redirect('home');
}

$album_id = $_GET['id'];

$album = new Album();
$a = $album->find($album_id);

$image = new Image();
$images = $image->getByAlbumID($album_id);
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
                <h1>View Album</h1>
                <?php
                $url = "listAlbum.php?user=" . $a->user_id;
                $u = new User();
                $name = $u->getNameById($a->user_id);
                echo "<h6><a href= ". $url . ">Show All Album By " . $name . "</a></h6>";
                ?>
                <?php
                if ($user->id == $a->user_id) {
                    echo '<a href="editAlbum.php?id=' . $album_id .
                        '" class="btn btn-md btn-primary">Edit Album</a>';
                    echo '<br>';
                };
                ?>
            </div>
            <div>
                <?php
                if (!empty($images)) {
                    foreach ($images as $i) {
                        echo "                <li class=\"photo-box\">
                    <div class=\"image-wrap\">
                        <img src=\"$i->URL\">
                    </div>
                    <div class=\"description\">
                        <h5><a href=\"viewImage.php?id=" . $i->id . "\">" . $i->name . "</a></h5>
                        <div>" . $i->description . "</div>
                        <div>" . $i->timestamp . "</div>
                    </div>
                </li>";
                    }
                }
                else {
                    echo "No Image";
                }
                ?>

            </div>
        </div>


    </body>
</html>
