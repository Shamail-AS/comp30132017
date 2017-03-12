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
    const DB_HOST = "eu-cdbr-azure-west-d.cloudapp.net";
    const DB_USER = "b920831ca30deb";
    const DB_PASS = "f8813d50";
    const DB_NAME = "acsm_297da127cca3bea";

    private static $instance = null;
    private $connection = null;
    private $build_log = false;

    private function __construct($host, $user, $pass, $db)
    {
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$db",$user,$pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DB(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
        }
        return self::$instance;
    }

    protected function raw($sql)
    {
        if(strpos($sql,"SELECT") !== FALSE){
            return $this->query($sql);
        }
        else{
            return $this->exec($sql);
        }
    }

    protected function select($table, $columns = null, $where = "1=1")
    {
        $cols = $columns ?? "*";
        if(is_array($columns)){
            $cols = implode(",", $columns);
        }

        $query = "SELECT $cols FROM $table WHERE $where";
        return $this->query($query);

    }

    protected function insert($table, $data)
    {
        $dataString = implode(", :", array_keys($data)); // the PDO parameterized query
        $colString = implode("`,`", array_keys($data));
        $statement = "INSERT INTO $table (`$colString`) VALUES (:$dataString)";
        return $this->exec($statement, $data);
    }

    protected function update($table, $id, $data)
    {
        unset($data['id']);
        $updateString = implode(",",$this->zipArray($data));
        $statement = "UPDATE $table SET $updateString WHERE `id`=$id";
        return $this->exec($statement,$data);
    }

    protected function deleteWhere($table, $where)
    {
        $statement = "DELETE FROM $table WHERE $where";
        return $this->exec($statement);
    }

    private function exec($statement, $values = null)
    {

        $db = self::getInstance();
        if ($this->build_log) {
            $log = $db->connection->quote($statement);
            $db->connection->exec("INSERT INTO `sql_log` VALUES(null,$log)");
        }

        if($values != null){
            $prepared = $db->connection->prepare($statement);

            $prepared->execute($values);

        }
        else{
            $db->connection->exec($statement);
        }
        return $db->connection->lastInsertId();
    }
    private function query($query)
    {
        $db = self::getInstance();
        if ($this->build_log) {
            $log = $db->connection->quote($query);
            $db->connection->exec("INSERT INTO `sql_log` VALUES(null,$log)");
        }
        $result = $db->connection->query($query);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        return $result->fetchAll();
    }

    private function zipArray($array)
    {
        $zipped = [];
        foreach ($array as $key=>$value){
            array_push($zipped, "`$key`=:$key");
        }
        return $zipped;
    }
}