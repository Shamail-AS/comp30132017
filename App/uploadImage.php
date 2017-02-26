 <?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 08/02/2017
 * Time: 16:35
 */
require_once('../Core/SessionManager.php');
require_once('../Models/Album.php');
require_once('../Core/Validator.php');
require_once('../Models/Privacy.php');
require_once('../Models/User.php');
require_once('../Models/Image.php');

use Database\Models\Image;
use Database\Models\Privacy;
use Http\Forms\Validator;
use Http\Session\SessionManager;
use Database\Models\Album;
use Database\Models\User;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;

$album = new Album();
$optList = $album->getByUser($user->id);
function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}
//pr($optList);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Upload Image</title>

    <!-- Bootstrap core CSS -->
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('common/nav.php') ?>
<div class="container">

    <div class="starter-template">
        <h1>Upload Image</h1>
    </div>
    <div>
        <form action="uploadImage.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="name"
                           placeholder="Name" name="title" required="">
                </div>
            </div>
            <div class="form-group">
                <select id="selAlbum" class="form-control" name="selAlbum" title="Select">
                    <option>Select Album</option>
                    <?php
                    foreach ($optList as $opt)
                    {
                        echo "<option id = $opt->id>".$opt->name."</option>";
                    }
                    ?>
                </select>

            </div>
            <div class="form-group">
                <div>
                    <textarea class="form-control" rows="10" placeholder="Description" name="content" required=""></textarea>
                </div>
            </div>
            <div class="form-group">
                <input type="file" name="fileToUpload" id="fileToUpload">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </form>
    </div>

</div>



</body>
</html>