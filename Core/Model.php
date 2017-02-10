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
        if (!$this->exists()) {
            $result = parent::insert($this->table, $this->data);
            $this->id = $result;
            return $this;
        } else {
            $result = parent::update($this->table, $this->id, $this->data);
            return $this;
        }

    }

    public function exists()
    {
        $exists = false;
        if ($this->id == null) return $exists;
        else {
            $sql = "SELECT * FROM $this->table WHERE `$this->primaryKey` = $this->id LIMIT 1";
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

    public function whereIn($cols = null, $col, $in)
    {
        $inStmt = "$col IN ( " . join(",", $in) . " )";
        return $this->where($cols, $inStmt);
    }

    public function raw($sql)
    {
        return $this->asModel(parent::raw($sql));
    }

    private static function asModel($db_records)
    {
        if (!is_array($db_records)) return $db_records;
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