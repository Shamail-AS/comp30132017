<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 10/02/2017
 * Time: 14:48
 */
require_once('../Models/Tag.php');
use Database\Models\Tag;

//var_dump($_POST);
if (isset($_POST) && !empty($_POST)) {
    if($_POST['action'] == "add") {
        $tag = new Tag();
        $tag->key = $_POST['text'];
        $tag->value = $_POST['text'];
        $tag->addNewTag($_POST['image_id']);
    }
    elseif ($_POST['action'] == "remove") {
        $tag = new Tag();
        $tag->removeTag($_POST['tag_id'], $_POST['image_id']);
    }
}
?>