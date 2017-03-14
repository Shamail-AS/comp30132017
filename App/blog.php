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

if (isset($_GET['user']) && !empty($_GET)) {
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

    <div class = "container">
        <?php if (!$canView) { ?>
            <div class="alert alert-danger">You can't view the blog because you two aren't Friend!!! Add as a friend through
                <a href="sendInvite.php">HERE</a></div>
            <?php exit();
        } ?>


        <h1>Blog</h1>
        <hr />

        <?php if (!empty($blogs)) {
            foreach ($blogs as $b) {
                echo '<li class="list-group-item">';
                echo '<div class = search-result>';
                echo '<h1>'.$b->post_title.'</h1>';
                echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($b->timestamp)).'</p>';
                echo '<p><a href = "viewPost.php?id= '.$b->id.'">Read More</a></p>';
                echo '</div>';
                echo'</li>';
            }
        }else {
            echo "No Blog Post";
        }?>

        <br>
        <?php if ($isViewingOwn) { ?> <a href="searchBlog.php" class="btn btn-primary" type = "button">Search Blog</a><?php } ?>
        <?php if ($isViewingOwn) { ?> <a href="addPost.php" class="btn btn-primary" type = "button">Add Post</a><?php } ?>

    </div>



</body>
</html>

