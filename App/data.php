<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 3/3/2017
 * Time: 9:32 PM
 */

require_once ('../Core/SessionManager.php');
require_once ('../Core/Model.php');
require_once ('../Models/User.php');

use Http\Session\SessionManager;
use Database\Models\User;

$session = new SessionManager();
$session->start();
$session->blockGuest();

$user = new User($session->user->getAllData());
$data = $user->getAllData();

$updateUser = new User();
$u = $updateUser->find($user->id);
$userData = $u->getAllData();

pr($user);

if (isset($_GET['export'])) {
    @date_default_timezone_set("GMT");
    $writer = new XMLWriter();

    $writer->openURI('data.xml');
    $writer->startDocument('1.0');
    $writer->setIndent(4);
    $writer ->startElement("User_Profile");
    foreach ($data as $key=> $value) {
        $writer->writeElement($key, $value);
    }
    $writer->endElement();
    $writer->endDocument();
}

if (isset($_GET['import'])){
    $reader = new XMLReader();
    if (!$reader->open('xml_file/data.xml')) {
        die("Failed to open data.xml");
    }

    while($reader->read()) {
        $node = $reader->expand();
        if(strpos($node->nodeName,'#text') > -1) continue;
        if(strlen($node->nodeValue) < 1) continue;
        if(!array_key_exists( $node->nodeName,$userData )) continue;
        echo($node->nodeName."<br>");
        $u->set($node->nodeName, $node->nodeValue);
    }
    var_dump($u->getAllData());
    var_dump(is_null($u->dob));
    $u->save();
}

function pr($data)
{
    echo "<pre>";
    print_r($data); // or var_dump($data);
    echo "</pre>";
}


?>

<!DOCTYPE html>
<html lang = 'en'>
<head>
    <title>Export</title>
    <script language="JavaScript" type="text/javascript">
        function exporting() {
            window.location.href = 'data.php?export';
            alert("Exported to xml_file folder");

        }
        function importing() {
            window.location.href = 'data.php?import';
            alert("Import from xml_file folder");

        }
    </script>
    <link href="../Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include('common/nav.php'); ?>
<p><input type='button' value='Export' onclick = 'exporting()'</p>
<p><input type='button' value='Import' onclick = 'importing()'</p>
</body>
</html>
