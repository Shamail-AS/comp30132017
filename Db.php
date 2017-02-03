<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 01/02/2017
 * Time: 09:11
 */

namespace Database\Connection;

use PDO;
use PDOException;

class DB
{
    public static const DB_HOST = "eu-cdbr-azure-west-d.cloudapp.net";
    public static const DB_USER = "b920831ca30deb";
    public static const DB_PASS = "f8813d50";
    public static const DB_NAME = "acsm_297da127cca3bea";

    private static $instance = null;
    private $connection = null;

    private function __construct($host, $user, $pass, $db)
    {
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$db",$user,$pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "OK";
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    public function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DB(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
        }
        return self::$instance;
    }

    public function raw($sql){
        if(strpos($sql,"SELECT") !== FALSE){
            return $this->query($sql);
        }
        else{
            return $this->exec($sql);
        }
    }
    public function select($table, $columns = null, $where = "1=1"){
        $cols = $columns ?? "*";
        if(is_array($columns)){
            $cols = implode(",", $columns);
        }

        $query = "SELECT $cols FROM $table WHERE $where";
        return $this->query($query);

    }

    public function insert($table, $data)
    {
        $dataString = implode("','", array_values($data));
        $colString = implode("','", array_keys($data));
        $statement = "INSERT INTO $table ('$colString') VALUES ('$dataString')";
        print $statement;
        return $this->exec($statement);
    }

    public function update($table, $id, $data)
    {
        $updateString = implode(",",$this->zipArray($data));
        $statement = "UPDATE TABLE $table SET $updateString WHERE `ID`=$id";
        print $statement;
        return $this->exec($statement);
    }

    public function delete($table, $id){
        $statement = "DELETE FROM $table WHERE `ID` = $id";
        print $statement;
        return $this->exec($statement);
    }

    private function exec($statement)
    {
        $affected = $this->connection->exec($statement);
        return $affected;
    }
    private function query($query)
    {
        $result = $this->connection->query($query);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        return $result->fetchAll();
    }

    private function zipArray($array)
    {
        $zipped = [];
        foreach ($array as $key=>$value){
            array_push($zipped,"$key=$value");
        }
        return $zipped;
    }
}