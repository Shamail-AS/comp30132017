<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 6/2/2017
 * Time: 5:32 PM
 */

require_once('../Models/BlogPost.php');
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');
include_once('../Models/Friendship.php');
include_once('../Core/PrivacyManager.php');


use Http\Session\SessionManager;
use Database\Models\Blog_post;
use Database\Models\User;
use Database\Core\PrivacyManager;
use Database\Models\Friendship;

$session = new SessionManager();
$session->start();
$session->blockGuest();

$user = $session ->user;

$u = new User();
$logged_user = new User($session->user->getAllData());
$view_user = $logged_user;
$isViewingOwn = true;
$canView = true;

if (isset($_GET) && !empty($_GET)) {
    $view_user = $u->find($_GET['user']);
    $f = new Friendship();
    if ($f->areFriends($view_user, $logged_user)) {
        $canView = True;
    }
    else {
        $canView = False;
    }
    $isViewingOwn = false;
}
$blog = new Blog_post();
$blogs = $blog->getByUser($view_user->id);

if(isset($_GET['delpost'])){

    $blog->deletePost($_GET['delpost']);

    header('Location: blog.php?action=deleted');
    exit;
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>

    <title>Blog</title>

    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php include('common/nav.php') ?>

    <?php if (!$canView) { ?>
        <div class="alert alert-danger">You are not his friend!!!</div>
        <?php exit();
    } ?>

    <h1>Blog</h1>
    <hr />

    <?php

    if (!empty($blogs)) {
        foreach ($blogs as $b) {

            echo '<div>';
                echo '<h1>'.$b->post_title.'</h1>';
                echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($b->timestamp)).'</p>';
                echo '<p><a href = "viewPost.php?id= '.$b->id.'">Read More</a></p>';
            echo '</div>';

        }
    }else {
        echo "No Blog Post";
    }


    ?>
    <?php if ($isViewingOwn) { ?> <h4><a href="searchBlog.php">Search Blog</a></h4><?php } ?>
    <?php if ($isViewingOwn) { ?> <h4><a href="addPost.php">Add Post</a></h4><?php } ?>


</body>
</html>

