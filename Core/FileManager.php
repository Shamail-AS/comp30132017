<?php
/**
 * Created by PhpStorm.
 * User: asdfg
 * Date: 11/03/2017
 * Time: 20:45
 */

namespace Database\Core;

require_once "../vendor/autoload.php";

use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException;


class FileManager
{

    private static $instance = null;
    private $blobClient = null;
    private function __construct()
    {
        $connectionString = 'DefaultEndpointsProtocol=https;AccountName=comp3013blob;AccountKey=cQ91zOw8c2DHjQLliApm/5ppXk8zNe12EvCtgfoUhR7erbSGO0ZLwvMNT3P5A/sIfGSALBXvO/5UxvZEixqJZw==;';
        $this->blobClient = ServicesBuilder::getInstance()->createBlobService($connectionString);
    }
    public static function getInstance()
    {
        if(self::$instance == null){
            self::$instance = new FileManager();
        }
        return self::$instance;
    }

    public function prepare($filename){
        return fopen($_FILES[$filename]["tmp_name"], "r");
    }

    public function upload($name, $content){
        try    {
            //Upload blob
            $this->blobClient->createBlockBlob("mycontainer", $name, $content);
            return "https://comp3013blob.blob.core.windows.net/mycontainer/" . $name;
        }
        catch(ServiceException $e){
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179439.aspx
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
            return false;
        }
    }

}