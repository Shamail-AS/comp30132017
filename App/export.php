<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 28/2/2017
 * Time: 5:35 PM
 */

require_once ('../Core/SessionManager.php');
require_once ('../Core/Model.php');
require_once ('../Models/User.php');

use Http\Session\SessionManager;
use Database\Models\User;

$session = new SessionManager();
$session->start();
$session->blockGuest();

$usernow = new User($session->user->getAllData());
$data = $usernow->getAllData();
$user = $session -> user;

$tab = "\t";
$br = "\n";
$xml = '<?xml version = "1.0" encoding = "UTF-8"?>'. $br;
$xml .= '<database>'.$br;

$xml .= $tab. '<User Profile>' . $br;
foreach ($data as $key=>$value) {
    $xml .= $tab. $tab. '<'.$key.'>'. $value .'</'. $key. '>'. $br;
}
$xml .= $tab. '</User Profile>' . $br;
$xml .= '</database>';

#echo $xml;

function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}
pr($xml);

if (isset($_POST)) {
    $name = $usernow->name;
    $handle = fopen($name. '_profile_backup_'.time(). '.xml', 'w+');
    fwrite($handle, $xml);
    fclose($handle);
}


?>

<!DOCTYPE html>
<html lang = 'en'>
<head>
    <title>
        Export
    </title>
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('common/nav.php'); ?>
<p><input type='submit' name='submit' value='Export'</p>
</body>
</html>

