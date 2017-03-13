<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 03/03/2017
 * Time: 14:29
 */
require_once('../Models/Album.php');
require_once('../Core/SessionManager.php');
use Database\Models\Album;
use Http\Session\SessionManager;
$session = new SessionManager();
$session->start();
$session->blockGuest();
$user = $session->user;

if (isset($_POST) && !empty($_POST)) {
    $album = new Album();
    $a = $album->getByID($_POST['id']);
    if ($_POST['action'] == 'edit') {
        $a->name = $_POST['name'];
        $a->privacy_level = $_POST['plevel'];
        $a->save();
        if (isset($_POST['selGroup']) && !empty($_POST['selGroup'])) {
            foreach ($_POST['selGroup'] as $circle) {
                $album->assignToCircle($_POST['id'], $circle);
            }
        }

        if (isset($_POST['delGroup']) && !empty($_POST['delGroup'])) {
            foreach ($_POST['delGroup'] as $circle) {
                $album->delWithCircle($_POST['id'], $circle);
            }
        }
    }
    elseif ($_POST['action'] == 'delete') {
        $a->remove($_POST['id']);
    }
}
?>