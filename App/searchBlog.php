<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 26/2/2017
 * Time: 2:22 PM
 */

require_once('../Core/SessionManager.php');
require_once('../Models/User.php');

use Http\Session\SessionManager;
use Database\Models\User;

$session = new SessionManager();
$session->start();
$session->blockGuest();

$users = [];
$searcher = "";
$searching = false;
if (isset($_GET['searcher']) && !empty($_GET['searcher'])) {
    $searcher = $_GET['searcher'];
    if (strlen($searcher) > 0) {
        $searching = true;
        $user = new User();
        $users = $user->where(null, "users.username LIKE '%$searcher%'");

    }
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
    <div class="container">
        <h1>Blog</h1>
        <hr />

        <form class="form-inline" method="get" action="SearchBlog.php">
            <div class="form-group">
                <input class="form-control" type="text" name="searcher" placeholder="Enter Username">
                <button class="btn btn-default" type="submit">Search</button>
            </div>
        </form>

        <?php if ($searching && count($users) == 0) { ?>
            <div class="alert alert-info">No results found for <?php echo $searcher ?></div>
        <?php } else {
            ?>
            <ul class="list-group">
                <?php foreach ($users as $user) { ?>
                    <li class="list-group-item">
                        <div class="search-result">
                            <p><?php echo $user->username ?></p>
                            <a href="blog.php?user=<?php echo $user->id ?>" class="btn btn-primary" type="button">Visit Blog</a>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>

    </div>


</body>
</html>