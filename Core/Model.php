<?php

namespace Database\Models;
require_once('Db.php');

use Database\Connection\DB;

class Model extends DB
{
    protected $table = "";
    protected $primaryKey = "id";
    protected $data = [];

    public function __construct(array $attributes = [])
    {
        $this->data = $attributes;
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    public function find($id)
    {
        $result = ($this->where(null, "id=$id"));
        if ($result == null) return null;
        if (count($result) == 0) return null;
        else {
            return $result[0];
        }
    }

    public function findByColumn($column, $value)
    {
        $result = ($this->where(null, "$column=$value"));
        if ($result == null) return null;
        if (count($result) == 0) return null;
        else {
            return $result;
        }
    }

    public function save()
    {
        $id = $this->__get('id');
        $exists = $this->exists();
        if ($exists == true) {
            unset($this->data["id"]);
            parent::update($this->table, $id, $this->data);
        } else {
            parent::insert($this->table, $this->data);
        }
    }

    public static function create()
    {
    }

    public function exists()
    {
        $exists = false;
        $id = $this->__get("id");
        if ($id == null) return $exists;
        else {
            $sql = "SELECT * FROM $this->table WHERE $this->primaryKey = $id  LIMIT 1";
            $result = parent::raw($sql);
            $exists = (count($result) > 0);
        }
        return $exists;
    }

    public function all($cols = null)
    {
        return $this->asModel(parent::select($this->table, $cols));
    }

    public function where($cols = null, $clause)
    {
        return $this->asModel(parent::select($this->table, $cols, $clause));
    }

    private static function asModel($db_records)
    {
        $models = [];
        if (count($db_records) > 0) {
            foreach ($db_records as $db_record) {
                array_push($models, self::newModel($db_record));
            }
        }
        return $models;

    }

    private static function newModel($attributes)
    {
        $model = new static($attributes);
        return $model;
    }


}