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
require_once "../vendor/autoload.php";
 require_once('../Models/Circles_Member.php');

use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException;

use Database\Models\Image;
use Database\Models\Privacy;
use Http\Forms\Validator;
use Http\Session\SessionManager;
use Database\Models\Album;
use Database\Models\User;
use Database\Models\Circles_Member;

$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;

$connectionString = 'DefaultEndpointsProtocol=https;AccountName=comp3013blob;AccountKey=cQ91zOw8c2DHjQLliApm/5ppXk8zNe12EvCtgfoUhR7erbSGO0ZLwvMNT3P5A/sIfGSALBXvO/5UxvZEixqJZw==;';
$blobClient = ServicesBuilder::getInstance()->createBlobService($connectionString);

$album = new Album();
$optList = $album->getByUser($user->id);
 if (isset($_POST) && !empty($_POST)) {
     pr($_FILES);
     $validator = new Validator();
     $errors = $validator->validateUserRegistrationData($_POST);
     if (count($errors) > 0) {
     foreach ($errors as $key => $value) {
         $session->addError($key, $value);
     }
     if ($session->hasErrors()) {
         $session->redirect('uploadImage');
     }
     } else {
         if ($_FILES["fileToUpload"]["size"] > 500000) {
             $session->addError("file", "File is too large");
             $session->redirect('uploadImage');
         }
         // Allow certain file formats
         if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
             && $imageFileType != "gif" ) {
             $session->addError("file", "Invalid Extension");
             $session->redirect('uploadImage');
         }

         // Create blob REST proxy.
         $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);
         $content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
         $blob_name = $_FILES["fileToUpload"]["name"];

         try    {
             //Upload blob
             $blobRestProxy->createBlockBlob("mycontainer", $blob_name, $content);
         }
         catch(ServiceException $e){
             // Handle exception based on error codes and messages.
             // Error codes and messages are here:
             // http://msdn.microsoft.com/library/azure/dd179439.aspx
             $code = $e->getCode();
             $error_message = $e->getMessage();
             echo $code.": ".$error_message."<br />";
         }

         $image = new Image();
         $image->name = $_POST['title'];
         $image->description = $_POST['content'];
         $image->album_id = $_POST['selAlbum'];
         $image->URL = "https://comp3013blob.blob.core.windows.net/mycontainer/" . $_FILES["fileToUpload"]["name"];
         $image->save();
         $newURL = "viewAlbum.php?id=" . $_POST['selAlbum'];
         header('Location: '. $newURL);
     }
 }
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
                <select class="form-control" name="selAlbum" title="Select">
                    <option>Select Album</option>
                    <?php
                    foreach ($optList as $opt)
                    {
                        echo "<option value = $opt->id>".$opt->name."</option>";
                    }
                    $sharedAlbum = new Album();
                    $circles = new Circles_Member();
                    $mycircles = $circles->getByUser($user->id);
                    if (count($mycircles) > 0) {
                        echo "<option style='font-weight: bold; padding: 6px;' disabled=\"disabled\">-----------------------------------</option>";
                        echo "<option style='font-weight: bold; padding: 6px;' disabled=\"disabled\">Album Shared By Circles</option>";
                        echo "<option style='font-weight: bold; padding: 6px;' disabled=\"disabled\">-----------------------------------</option>";
                        foreach ($mycircles as $c) {
                            //if ($c->user != $user_id) {
                            $sharedAlbums = $sharedAlbum->getAlbumByCircleID($c->circle);
                            foreach ($sharedAlbums as $s) {
                                if ($s->user_id != $user_id) {
                                    echo "<option value = $s->id>" . $s->name . "</option>";
                                }
                            }
                        }
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