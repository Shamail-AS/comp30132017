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
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="searchUsers.php">Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manageInvites.php">Requests</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#"><?php echo $user->name ?></a>
            </li>
        </ul>
        <!--        <form class="form-inline mt-2 mt-md-0">-->
        <!--            <input class="form-control mr-sm-2" type="text" placeholder="Search">-->
        <!--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
        <!--        </form>-->
    </div>
</nav>

