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

use Database\Models\Image;
use Database\Models\Privacy;
use Http\Forms\Validator;
use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();

$privacy = new Privacy();
$optList = $privacy->listAll();
function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}
//pr($optList);
if (isset($_POST) && !empty($_POST)) {

    $validator = new Validator();
    $errors = $validator->validateUserRegistrationData($_POST);
    if (count($errors) > 0) {
        foreach ($errors as $key => $value) {
            $session->addError($key, $value);
        }
    } else {
        //$session->clean();
    }
    //var_dump($session->errors());
    if ($session->hasErrors()) {
        //add redirection back to form
        $session->redirect('uploadImage');
        //var_dump($session->errors());
    } else {
        $image = new Image();
        $image->name = $_POST['name'];
        $image->description = $_POST['description'];
        $image->album_id = $_POST['album_id'];


    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Make a Post</title>

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
                <select id="selAlbum" class="form-control" title="Select">
                    <option>Select Album</option>
                    <?php
                    foreach ($optList as $opt)
                    {
                        echo "<option id = $opt->id>".$opt->description."</option>";
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