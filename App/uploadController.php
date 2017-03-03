<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 03/03/2017
 * Time: 09:39
 */
function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}
if (isset($_POST) && !empty($_POST)) {
    pr($_POST);
    //pr($_FILES['fileToUpload']);
    //move_uploaded_file($_FILES['fileToUpload']['tmp_name'], 'uploads/' . $_FILES['fileToUpload']['tmp_name']);
}
?>