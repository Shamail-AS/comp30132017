<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 07/02/2017
 * Time: 11:39
 */
require_once('../Core/SessionManager.php');
require_once('../Models/User.php');

use Database\Models\User;
use Http\Session\SessionManager;

$session = new SessionManager();
$user = $session->user;

if ($user == null) {
    $user = new User();
    $user->name = "Guest";
} else {
}
?>
<style>
    body {
        min-height: 75rem;
        padding-top: 4.5rem;
    }
</style>
<nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="home.php">Soci.al</a>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <?php
                if ($user->name != "Guest") {
                    $url = "blog.php";
                    echo "<li class=\"nav-item\"><a class=\"nav-link\" href= " . $url . ">My Blog</a></li>";
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if ($user->name != "Guest") {
                    $url = "listAlbum.php?user=" . $user->id;
                    echo "<li class=\"nav-item\"><a class=\"nav-link\" href= " . $url . ">My Album</a></li>";
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if ($user->name != "Guest") {
                    echo '<a class="nav-link" href = "logout.php" > Logout</a >';
                }
                else {
                    echo '<a class="nav-link" href = "register.php" > Register</a >';
                }
                ?>
                <?php
                if ($user->name != "Guest") {
                    echo '<li class="nav-item">
                <a class="nav-link" href="searchUsers.php">Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manageInvites.php">Requests</a>
            </li>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="listCircle.php">My Circles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="uploadImage.php">Upload Image</a>
            </li>';
                }
                ?>

            <?php
            if ($user != null && $user->usertype == "ADMIN") {
                echo "<li class=\"nav-item\">";
                echo "<a class=\"nav-link\" href=\"admin.php\">Admin</a>";
                echo "</li>";
            }
            ?>
            <li class="nav-item">
                <a class="nav-link " href="viewProfile.php"><?php echo $user->name ?></a>
            </li>
            </li>
        </ul>
        <!--        <form class="form-inline mt-2 mt-md-0">-->
        <!--            <input class="form-control mr-sm-2" type="text" placeholder="Search">-->
        <!--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
        <!--        </form>-->
    </div>
</nav>

