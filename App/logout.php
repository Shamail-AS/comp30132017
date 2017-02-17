<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 16/02/2017
 * Time: 23:20
 */

require_once('../Core/SessionManager.php');

use Http\Session\SessionManager;

$session = new SessionManager();
$session->start();
$session->end();

echo "Logged out";
var_dump($_SESSION);
$session->redirect('login');